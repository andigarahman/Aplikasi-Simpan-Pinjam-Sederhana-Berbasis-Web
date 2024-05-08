<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Data Pinjaman</title>
    <!-- Include your CSS and JS files here -->
    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico" />
</head>
<body>
    <?php include 'header.php' ?>
    <?php include 'slidebar_anggota.php' ?>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
     
                            <h2 class="card-title">Data Pinjaman</h2>

                            <?php
                            // Include your database connection code here
                            include '../control/koneksi.php';

                            // Fetch transfer_peminjaman and angsuran_berlangsung data for the logged-in anggota
                            $id_anggota = $_SESSION['id_anggota'];
                            $querySelect = "SELECT ab.id_angsuran, ab.id_transaksi, ab.jumlah_angsuran, ab.tanggal_disetujui, ab.status, ab.bukti_transfer, pp.lama_angsuran, DATE_ADD(ab.tanggal_disetujui, INTERVAL pp.lama_angsuran MONTH) AS tenggat_waktu
                                            FROM angsuran_berlangsung ab
                                            LEFT JOIN permintaan_peminjaman pp ON ab.id_transaksi = pp.id_transaksi
                                            WHERE ab.id_anggota = '$id_anggota'";
                            $result = mysqli_query($conn, $querySelect);

                            if (!$result) {
                                echo "Error fetching data: " . mysqli_error($conn);
                                mysqli_close($conn);
                                exit();
                            }
                            ?>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID Angsuran</th>
                                        <th>ID Transaksi</th>
                                        <th>Jumlah Angsuran</th>
                                        <th>Tanggal Disetujui</th>
                                        <th>Status</th>
                                        <th>Lama Angsuran</th>
                                        <th>Tenggat Waktu</th>
                                        <th>Bukti Transfer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $row["id_angsuran"] . "</td>";
                                            echo "<td>" . $row["id_transaksi"] . "</td>";
                                            echo "<td>" . $row["jumlah_angsuran"] . "</td>";
                                            echo "<td>" . $row["tanggal_disetujui"] . "</td>";
                                            echo "<td>" . $row["status"] . "</td>";
                                            echo "<td>" . $row["lama_angsuran"] . " bulan</td>";
                                            echo "<td>" . $row["tenggat_waktu"] . "</td>";

                                            // Display bukti_transfer image (assuming it's stored in the 'uploads' folder)
                                            echo "<td><a href='../control/uploads/bukti_transfer/" . $row["bukti_transfer"] . "' target='_blank'><img src='../control/uploads/bukti_transfer/" . $row["bukti_transfer"] . "' alt='Bukti Transfer' style='max-width: 200px; max-height: 200px;'></a></td>";

                                            // Added action to open pembayaran_angsuran.php with the respective id_transaksi
                                            echo "<td><a href='pembayaran_angsuran_test.php?id_transaksi=" . $row["id_transaksi"] . "' class='btn btn-gradient-primary me-2'>Bayar Angsuran</a></td>";

                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='9'>Tidak ada data pinjaman untuk anggota ini.</td></tr>";
                                    }

                                    mysqli_close($conn);
                                    ?>
                                </tbody>
                            </table>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "../public/footer.php" ?>
</body>
</html>
