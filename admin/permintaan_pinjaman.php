<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Permintaan Pinjam</title>
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

<?php
// Database connection
$koneksi = include '../control/koneksi.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch permintaan peminjaman data
$querySelect = "SELECT * FROM permintaan_peminjaman";
$result = mysqli_query($conn, $querySelect);

if (!$result) {
    echo "Error fetching data: " . mysqli_error($conn);
    mysqli_close($conn);
    exit();
}
?>

<body>
<?php include 'header.php'; ?>
<?php include 'slidebar_admin.php'; ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <h2 class="card-title">Permintaan Peminjaman</h2>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>ID Anggota</th>
                                    <th>Jumlah Peminjaman</th>
                                    <th>Lama Angsuran</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                    <th>Bukti Transfer</th> <!-- New Column -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    // Output data of each row
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $row["id_transaksi"] . "</td>";
                                        echo "<td>" . $row["id_anggota"] . "</td>";
                                        echo "<td>" . $row["jumlah_peminjaman"] . "</td>";
                                        echo "<td>" . $row["lama_angsuran"] . " Bulan</td>";
                                        echo "<td>" . $row["tanggal_pengajuan"] . "</td>";
                                        echo "<td>" . $row["status"] . "</td>";

                                        // Display Bukti Transfer
                                        echo "<td>";
                                        if (!empty($row["bukti_transfer"])) {
                                            echo "<a href='../uploads/bukti_transfer/" . $row["bukti_transfer"] . "' target='_blank'>Lihat Bukti</a>";
                                        } else {
                                            echo "Belum Diunggah";
                                        }
                                        echo "</td>";

                                        // Link to view syarat_peminjaman (PDF)
                                        echo "<td><a href='view_syarat.php?id=" . $row["id_transaksi"] . "' target='_blank' class='btn btn-primary'>Lihat Syarat</a>";

                                        // If status is 'menunggu', show approve and reject buttons
                                        if ($row["status"] == 'menunggu') {
                                            echo "<a href='../control/aprrove_test.php?id=" . $row["id_transaksi"] . "' class='btn btn-success'>Terima</a>";
                                            echo "<a href='../control/reject.php?id=" . $row["id_transaksi"] . "' class='btn btn-danger'>Tolak</a></td>";
                                        } else {
                                            echo "</td>";
                                        }

                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>Tidak ada permintaan peminjaman.</td></tr>";
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
</div>
<?php include "../public/footer.php" ?>
</body>
</html>
