<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Transaksi Simpan Saya</title>
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

// Fetch transaksi_simpan data
$id_anggota = $_SESSION['id_anggota'];
$querySelect = "SELECT * FROM transaksi_simpan WHERE id_anggota = '$id_anggota'";
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
                    <h2 class="card-title">Transaksi Simpan Saya</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Jumlah Transfer</th>
                                <th>Status</th>
                                <th>Tanggal Simpan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                // Output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row["id_transaksi"] . "</td>";
                                    echo "<td>" . $row["jumlah_transfer"] . "</td>";
                                    echo "<td>" . $row["status"] . "</td>";
                                    echo "<td>" . $row["tanggal_simpan"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>Tidak ada transaksi simpan.</td></tr>";
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
