<?php
if (isset($_POST['btn_logout'])) {
    session_start();
    session_destroy();
    echo "<script type= 'text/javascript'>
    alert('Anda Telah Keluar!');
    document.location.href = '/nsp/login.php';
    </script>";
    exit();
}
?>

<aside class="main-sidebar sidebar-dark-primary bg-blue shadow-lg elevation-5">
    <div class="brand-link bg-blue" style="border: none">
        <img src="/nsp/storage/nsp.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text text-light">Net Sun Power</span>
    </div>

    <div class="sidebar">
        <nav class="mt-2 ">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/nsp/admin/index.php" class="nav-link">
                        <ion-icon name="clipboard-outline" class="far nav-icon"></ion-icon>
                        <p class="text-light">Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <ion-icon name="archive-outline" class="far nav-icon"></ion-icon>
                        <p class="text-light">
                            Gudang
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/nsp/admin/gudang/inventaris.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Inventaris</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/nsp/admin/gudang/material.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Material</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item ">
                    <a href="#" class="nav-link">
                        <ion-icon name="people-outline" class="far nav-icon"></ion-icon>
                        <p class="text-light">
                            Karyawan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/nsp/admin/karyawan/datakaryawan.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Data Karyawan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/nsp/admin/karyawan/absen.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Data Absen</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item ">
                    <a href="/nsp/admin/pelanggan/pelanggan.php" class="nav-link">
                        <ion-icon name="people-circle-outline" class="far nav-icon"></ion-icon>
                        <p class="text-light">
                            Pelanggan
                            <i class="right fas"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="#" class="nav-link">
                        <ion-icon name="code-working-outline" class="far nav-icon"></ion-icon>
                        <p class="text-light">
                            Order Pekerjaan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/nsp/admin/pekerjaan/psb.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Pemasangan Baru</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/nsp/admin/pekerjaan/perbaikan.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Perbaikan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/nsp/admin/pekerjaan/request.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Requst Pelanggan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <ion-icon name="document-text-outline" class="far nav-icon"></ion-icon>
                        <p class="text-light">
                            Laporan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/nsp/admin/laporan/laporan-barang.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Penggunaan Barang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/nsp/admin/laporan/laporan-pekerjaan.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Perkerjaan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/nsp/admin/laporan/barang-masuk.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Barang Masuk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/nsp/admin/laporan/laporan-absen.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Absen Pegawai</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/nsp/admin/laporan/laporan-performa.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Performa Pegawai</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/nsp/admin/laporan/laporan-pelanggan.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Laporan Pelanggan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/nsp/admin/laporan/laporan-pemasangan.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Laporan Pemasangan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/nsp/admin/laporan/laporan-perbaikan.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Laporan Perbaikan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/nsp/admin/laporan/laporan-inventaris.php" class="nav-link">
                                <i class="far nav-icon"></i>
                                <p class="text-light">Laporan Inventaris</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item">
                    <form action="" method="POST">
                        <button type="submit" class="btn" name="btn_logout">
                            <p class="text-light">Logout</p>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>