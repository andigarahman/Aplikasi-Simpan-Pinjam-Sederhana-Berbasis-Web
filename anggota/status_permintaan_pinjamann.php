<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Transaksi Permintaan Peminjaman Saya</title>
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

// Fetch permintaan_peminjaman data
$id_anggota = $_SESSION['id_anggota'];
$querySelect = "SELECT * FROM permintaan_peminjaman WHERE id_anggota = '$id_anggota'";
$result = mysqli_query($conn, $querySelect);

if (!$result) {
    echo "Error fetching data: " . mysqli_error($conn);
    mysqli_close($conn);
    exit();
}
?>

<body>
<?php include 'header.php'; ?>
<?php include 'slidebar_anggota.php'; ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Transaksi Permintaan Peminjaman Saya</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>ID Anggota</th>
                                <th>Jumlah Peminjaman</th>
                                <th>Lama Angsuran</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
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
                                    echo "<td>" . $row["lama_angsuran"] . "</td>";
                                    echo "<td>" . $row["tanggal_pengajuan"] . "</td>";
                                    echo "<td>" . $row["status"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>Tidak ada transaksi permintaan peminjaman.</td></tr>";
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
