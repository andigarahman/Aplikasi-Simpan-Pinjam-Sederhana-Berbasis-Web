            <nav class="sidebar sidebar-offcanvas " id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <a href="dashboard_admin.php" class="nav-link">
                        <div class="nav-profile-text d-flex flex-column">
                            <span class="font-weight-bold mb-2"><?php echo $_SESSION['nama']?></span>
                            <span class="text-secondary text-small">Admin</span>
                        </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard_admin.php">
                        <span class="menu-title">Dashboard</span>
                        <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tambah_anggota.php">
                        <span class="menu-title">Tambah Anggota</span>
                        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                            <span class="menu-title">Permintaan Transaksi</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                        </a>
                        <div class="collapse" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="permintaan_simpan.php">Permintaan Simpan</a></li>
                            <li class="nav-item"> <a class="nav-link" href="permintaan_pinjaman.php">Permintaan Peminjaman</a></li>
                            <li class="nav-item"> <a class="nav-link" href="permintaan_pAngsuran.php">Pembayaran Angsuran</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="history_simpan.php">
                        <span class="menu-title">Riwayat Simpan</span>
                        <i class="mdi mdi-chart-bar menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_anggota.php">
                        <span class="menu-title">Data Anggota</span>
                        <i class="mdi mdi-table-large menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../control/logout.php">
                        <span class="menu-title">Logout</span>
                        <i class="mdi mdi-power menu-icon"></i>
                        </a>
                    </li>
                </ul>
              </nav>