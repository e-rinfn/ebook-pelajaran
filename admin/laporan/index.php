<?php
require_once '../../config/database.php';
require_once '../../includes/auth-check.php';
require_once '../../includes/functions.php';

$search = $_GET['search'] ?? '';
$kategori_id = $_GET['kategori_id'] ?? '';

// Mengambil semua kategori untuk dropdown
$query_kategori = "SELECT id, nama FROM kategori ORDER BY nama";
$stmt_kategori = $pdo->query($query_kategori);
$kategories = $stmt_kategori->fetchAll(PDO::FETCH_ASSOC);

// Membangun query dengan filter
$params = [];
$query = "SELECT ebook.*, GROUP_CONCAT(kategori.nama SEPARATOR ', ') as kategori_nama 
          FROM ebook 
          LEFT JOIN ebook_kategori ON ebook.id = ebook_kategori.ebook_id 
          LEFT JOIN kategori ON ebook_kategori.kategori_id = kategori.id 
          WHERE (ebook.judul LIKE ? OR ebook.penulis LIKE ?)";

$params[] = "%$search%";
$params[] = "%$search%";

// Menambahkan filter kategori jika dipilih
if (!empty($kategori_id) && is_numeric($kategori_id)) {
    $query .= " AND kategori.id = ?";
    $params[] = $kategori_id;
}

$query .= " GROUP BY ebook.id";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$ebooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Header -->
<?php include '../../includes/head.php'; ?>
<!-- /Header -->

<body class="">
    <div class="page">
        <div class="page-main">
            <div class="header py-4">

                <!-- Navbar -->
                <?php include '../../includes/navbar.php'; ?>
                <!-- / Navbar -->

                <div class="my-3 my-md-5">
                    <div class="container">
                        <div class="page-header">
                            <h1 class="page-title">
                                LAPORAN E-BOOK
                            </h1>
                        </div>

                        <div class="action-bar mb-3 d-flex flex-wrap justify-content-between align-items-center">
                            <form method="GET" class="form-inline mb-2 mb-md-0">

                                <div class="input-group mr-2 mb-2">
                                    <select name="kategori_id" class="form-control no-chevron">
                                        <option value="">Semua Kategori</option>
                                        <?php foreach ($kategories as $kategori): ?>
                                            <option value="<?= $kategori['id'] ?>" <?= ($kategori_id == $kategori['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($kategori['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <style>
                                    .no-chevron {
                                        -webkit-appearance: none;
                                        -moz-appearance: none;
                                        appearance: none;
                                        background-image: none !important;
                                    }
                                </style>


                                <div class="input-group mr-2 mb-2">
                                    <input type="text" name="search" class="form-control" placeholder="Cari judul atau penulis..." value="<?= htmlspecialchars($search ?? '') ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Cari</button>
                                    </div>
                                    <div class="input-group-append">
                                        <a href="index.php" class="btn btn-warning">Reset</a>
                                    </div>
                                </div>
                            </form>
                            <div>
                                <a href="cetak_excel.php?search=<?= urlencode($search) ?>&kategori_id=<?= urlencode($kategori_id) ?>" class="btn btn-success ml-2">
                                    <i class="fe fe-download"></i> Export Excel
                                </a>
                                <a href="cetak_pdf.php?search=<?= urlencode($search) ?>&kategori_id=<?= urlencode($kategori_id) ?>" class="btn btn-danger ml-2" target="_blank">
                                    <i class="fe fe-printer"></i> Cetak PDF
                                </a>
                            </div>
                        </div>

                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success">
                                E-Book berhasil <?= $_GET['success'] === 'add' ? 'ditambahkan' : 'diperbarui' ?>!
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <div class="row row-cards row-deck">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title text-center">TABEL DAFTAR EBOOK</h3>
                                        <?php if (!empty($search) || !empty($kategori_id)): ?>
                                            <div class="card-options">
                                                <span class="badge badge-info">
                                                    <?php
                                                    $filter_info = [];
                                                    if (!empty($search)) $filter_info[] = "Pencarian: " . htmlspecialchars($search);
                                                    if (!empty($kategori_id)) {
                                                        $selected_kategori = array_filter($kategories, function ($k) use ($kategori_id) {
                                                            return $k['id'] == $kategori_id;
                                                        });
                                                        if (!empty($selected_kategori)) {
                                                            $selected_kategori = reset($selected_kategori);
                                                            $filter_info[] = "Kategori: " . htmlspecialchars($selected_kategori['nama']);
                                                        }
                                                    }
                                                    echo implode(" | ", $filter_info);
                                                    ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-outline table-vcenter text-nowrap card-table table-bordered">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>No</th>
                                                    <th>Cover</th>
                                                    <th>Judul</th>
                                                    <th>Penulis</th>
                                                    <th>Kategori</th>
                                                    <th>Tahun</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($ebooks)): ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center py-4">
                                                            <div class="text-muted">Tidak ada data e-book yang ditemukan.</div>
                                                        </td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php $no = 1;
                                                    foreach ($ebooks as $ebook): ?>
                                                        <tr>
                                                            <td class="text-center"><?= $no++ ?></td>
                                                            <td class="text-center">
                                                                <?php if ($ebook['cover_url']): ?>
                                                                    <a href="../../uploads/covers/<?= htmlspecialchars($ebook['cover_url']) ?>" target="_blank" rel="noopener noreferrer">
                                                                        <img src="../../uploads/covers/<?= htmlspecialchars($ebook['cover_url']) ?>" alt="Cover" width="50" class="img-thumbnail">
                                                                    </a>
                                                                <?php else: ?>
                                                                    <div class="no-cover text-muted">No Cover</div>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <div class="font-weight-bold"><?= htmlspecialchars($ebook['judul']) ?></div>
                                                                <div class="small text-muted"><?= htmlspecialchars($ebook['bahasa'] ?? '-') ?></div>
                                                            </td>
                                                            <td><?= htmlspecialchars($ebook['penulis']) ?></td>
                                                            <td>
                                                                <?php if (!empty($ebook['kategori_nama'])): ?>
                                                                    <?php foreach (explode(',', $ebook['kategori_nama']) as $kategori): ?>
                                                                        <span class="badge badge-secondary"><?= htmlspecialchars(trim($kategori)) ?></span>
                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <span class="badge badge-secondary">Tidak terkategori</span>
                                                                <?php endif; ?>
                                                            </td>

                                                            <td class="text-center"><?= $ebook['tahun_terbit'] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer">
                <div class="container">
                    <div class="row align-items-center flex-row-reverse">
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
                            Copyright © 2025 <a href=".">E-Book Buku Pelajaran</a>.
                        </div>
                    </div>
                </div>
            </footer>
        </div>
</body>

</html>