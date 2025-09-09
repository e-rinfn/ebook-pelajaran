<?php
require_once '../../config/database.php';
require_once '../../includes/auth-check.php';

$search = $_GET['search'] ?? '';

// Query data ebook
$query = "SELECT ebook.*, GROUP_CONCAT(kategori.nama SEPARATOR ', ') as kategori_nama 
          FROM ebook 
          LEFT JOIN ebook_kategori ON ebook.id = ebook_kategori.ebook_id 
          LEFT JOIN kategori ON ebook_kategori.kategori_id = kategori.id 
          WHERE ebook.judul LIKE ? OR ebook.penulis LIKE ?
          GROUP BY ebook.id";
$stmt = $pdo->prepare($query);
$stmt->execute(["%$search%", "%$search%"]);
$ebooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Header for excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"laporan_ebook_" . date('Ymd_His') . ".xls\"");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        .text {
            mso-number-format: \@;
        }
    </style>
</head>

<body>
    <table border="1">
        <tr>
            <th colspan="8" style="text-align: center; font-size: 16px; background-color: #cccccc;">
                LAPORAN DAFTAR E-BOOK
            </th>
        </tr>
        <?php if (!empty($search)): ?>
            <tr>
                <th colspan="8" style="text-align: center;">
                    Hasil pencarian: <b><?= htmlspecialchars($search) ?></b>
                </th>
            </tr>
        <?php endif; ?>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Kategori</th>
            <th>Tahun</th>
            <th>Penerbit</th>
            <th>ISBN</th>
            <th>Tanggal Upload</th>
        </tr>
        <?php $no = 1;
        foreach ($ebooks as $ebook): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($ebook['judul']) ?></td>
                <td><?= htmlspecialchars($ebook['penulis']) ?></td>
                <td><?= $ebook['kategori_nama'] ?? '-' ?></td>
                <td><?= $ebook['tahun_terbit'] ?></td>
                <td><?= htmlspecialchars($ebook['penerbit'] ?? '-') ?></td>
                <td class="text"><?= htmlspecialchars($ebook['isbn'] ?? '-') ?></td>
                <td><?= date('d/m/Y', strtotime($ebook['created_at'])) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>