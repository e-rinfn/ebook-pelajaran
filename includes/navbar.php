<?php
// Mendapatkan nama file saat ini
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>

<!-- Navbar -->
<div class="container">
    <div class="d-flex">
        <a class="navbar-brand d-flex align-items-center" href="/ebook/admin/dashboard.php">
            <img src="/ebook/assets/images/Logo.png" alt="Logo" class="mr-2" style="height: 40px;">
            <span class="font-weight-bold text-dark">E-Book Buku Pelajaran</span>
        </a>
    </div>
</div>

<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3 ml-auto"></div>
            <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                    <li class="nav-item">
                        <a href="/ebook/admin/dashboard.php"
                            class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
                            <i class="fe fe-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ebook/admin/ebook/index.php"
                            class="nav-link <?= ($current_dir == 'ebook') ? 'active' : '' ?>">
                            <i class="fe fe-book"></i> E-Book
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ebook/admin/kategori/index.php"
                            class="nav-link <?= ($current_dir == 'kategori') ? 'active' : '' ?>">
                            <i class="fe fe-loader"></i> Kategori
                        </a>
                    </li>

                    <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
                        <li class="nav-item">
                            <a href="/ebook/admin/users/index.php"
                                class="nav-link <?= ($current_dir == 'users') ? 'active' : '' ?>">
                                <i class="fe fe-users"></i> Pengguna
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a href="/ebook/admin/profile.php"
                            class="nav-link <?= ($current_page == 'profile.php') ? 'active' : '' ?>">
                            <i class="fe fe-user"></i> Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ebook/auth/logout.php" class="nav-link">
                            <i class="fe fe-log-out"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>