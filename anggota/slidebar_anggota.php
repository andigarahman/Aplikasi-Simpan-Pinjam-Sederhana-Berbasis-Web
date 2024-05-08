
            <nav class="sidebar sidebar-offcanvas " id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <a href="dashboard_anggota.php" class="nav-link">
                        <div class="nav-profile-text d-flex flex-column">
                            <span class="font-weight-bold mb-2"><?php echo $_SESSION['nama']?></span>
                            <span class="text-secondary text-small">Anggota</span>
                        </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard_anggota.php">
                        <span class="menu-title">Dashboard</span>
                        <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="simpan.php">
                        <span class="menu-title">Tambah Simpanan</span>
                        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pinjam.php">
                        <span class="menu-title">Pinjam</span>
                        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pinjaman.php">
                        <span class="menu-title">Data Peminjaman</span>
                        <i class="mdi mdi-table-large menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                            <span class="menu-title">Transaksi</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                        </a>
                        <div class="collapse" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="transaksi_simpan_saya.php">Riwayat Transaksi Simpan</a></li>
                            <li class="nav-item"> <a class="nav-link" href="transaksi_angsuran_saya.php">Riwayat Transaksi Angsuran</a></li>
                            <li class="nav-item"> <a class="nav-link" href="status_permintaan_pinjamann.php">Riwayat Permintaan Pinjam</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../control/logout.php">
                        <span class="menu-title">Logout</span>
                        <i class="mdi mdi-power menu-icon"></i>
                        </a>
                    </li>
                </ul>
              </nav>
