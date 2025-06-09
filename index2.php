<!-- Header -->
<?php include './includes/head.php'; ?>
<!-- /Header -->


<body class="">
    <div class="page">
        <div class="page-main">
            <div class="header py-4">

                <!-- Navbar -->
                <div class="container">
                    <div class="d-flex">
                        <a class="header-brand" href="./index.html">
                            <img src="./demo/brand/tabler.svg" class="header-brand-img" alt="tabler logo">
                        </a>


                        <!-- Dropdown Navbar -->
                        <div class="d-flex order-lg-2 ml-auto">
                            <div class="dropdown">
                                <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                                    <span class="avatar" style="background-image: url(./demo/faces/female/25.jpg)"></span>
                                    <span class="ml-2 d-none d-lg-block">
                                        <span class="text-default">Jane Pearson</span>
                                        <small class="text-muted d-block mt-1">Administrator</small>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="#">
                                        <i class="dropdown-icon fe fe-user"></i> Profile
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="dropdown-icon fe fe-log-out"></i> Sign out
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- / Dropdown Navbar -->


                        <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                            <span class="header-toggler-icon"></span>
                        </a>
                    </div>
                </div>
            </div>


            <!-- Navbar -->
            <?php include './includes/navbar.php'; ?>
            <!-- / Navbar -->

            <div class="my-3 my-md-5">
                <div class="container">
                    <div class="page-header">
                        <h1 class="page-title">
                            JUDUL HALAMAN
                        </h1>
                    </div>

                    <!-- Cards Invormasi-->
                    <div class="row row-cards">
                        <div class="col-6 col-sm-4 col-lg-2">
                            <div class="card">
                                <div class="card-body p-3 text-center">
                                    <div class="text-right text-red">
                                        -3%
                                        <i class="fe fe-chevron-down"></i>
                                    </div>
                                    <div class="h1 m-0">17</div>
                                    <div class="text-muted mb-4">Closed Today</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-2">
                            <div class="card">
                                <div class="card-body p-3 text-center">
                                    <div class="text-right text-green">
                                        9%
                                        <i class="fe fe-chevron-up"></i>
                                    </div>
                                    <div class="h1 m-0">7</div>
                                    <div class="text-muted mb-4">New Replies</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3 class="">
                        E-Book Terbaru
                    </h3>

                    <div class="row row-cards row-deck">
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table table-hover table-outline table-vcenter text-nowrap card-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center w-1"><i class="icon-people"></i></th>
                                                <th>Judul</th>
                                                <th>Penulis</th>
                                                <th class="text-center">Tahun</th>
                                                <th>Tanggal Upload</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Tambahkan isi disini -->
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
                    <div class="col-auto ml-lg-auto">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <ul class="list-inline list-inline-dots mb-0">
                                    <li class="list-inline-item"><a href="./docs/index.html">Documentation</a></li>
                                    <li class="list-inline-item"><a href="./faq.html">FAQ</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
                        Copyright © 2025 <a href=".">E-Book Buku Pelajaran</a>.
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>