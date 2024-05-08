<?php
session_start();

$user = "root";
$pw = "";
$db = "koperasi_sp";
$host = "localhost";

$conn = mysqli_connect($host, $user, $pw, $db);

if (!$conn) {
    die("Connection to the database failed: " . mysqli_connect_error());
}

$id_anggota = $_SESSION['id_anggota'];

// Debugging - Check if the session variable is set
var_dump($id_anggota);

// Fetch simpan_pokok value from the database using prepared statement
$sqlSimpanPokok = "SELECT simpan_pokok FROM anggota WHERE id_anggota = ?";
$stmtSimpanPokok = mysqli_prepare($conn, $sqlSimpanPokok);

if ($stmtSimpanPokok) {
    mysqli_stmt_bind_param($stmtSimpanPokok, "s", $id_anggota);
    mysqli_stmt_execute($stmtSimpanPokok);
    $resultSimpanPokok = mysqli_stmt_get_result($stmtSimpanPokok);

    // Debugging - Check if the query was successful
    var_dump($resultSimpanPokok);

    if ($resultSimpanPokok) {
        $rowSimpanPokok = mysqli_fetch_assoc($resultSimpanPokok);

        // Debugging - Check the fetched row
        var_dump($rowSimpanPokok);

        $simpan_pokok = $rowSimpanPokok ? $rowSimpanPokok['simpan_pokok'] : "N/A";
    } else {
        // Handle error if the query fails
        $simpan_pokok = "N/A";
    }

    mysqli_stmt_close($stmtSimpanPokok);
} else {
    // Handle error if the prepared statement fails
    $simpan_pokok = "N/A";
}

// Fetch total pinjaman value from the database using prepared statement
$sqlTotalPinjaman = "SELECT SUM(jumlah_angsuran) AS total_pinjaman FROM angsuran_berlangsung WHERE id_anggota = ?";
$stmtTotalPinjaman = mysqli_prepare($conn, $sqlTotalPinjaman);

if ($stmtTotalPinjaman) {
    mysqli_stmt_bind_param($stmtTotalPinjaman, "s", $id_anggota);
    mysqli_stmt_execute($stmtTotalPinjaman);
    $resultTotalPinjaman = mysqli_stmt_get_result($stmtTotalPinjaman);

    // Debugging - Check if the query was successful
    var_dump($resultTotalPinjaman);

    if ($resultTotalPinjaman) {
        $rowTotalPinjaman = mysqli_fetch_assoc($resultTotalPinjaman);

        // Debugging - Check the fetched row
        var_dump($rowTotalPinjaman);

        $total_pinjaman = $rowTotalPinjaman ? $rowTotalPinjaman['total_pinjaman'] : 0;
    } else {
        // Handle error if the query fails
        $total_pinjaman = 0;
    }

    mysqli_stmt_close($stmtTotalPinjaman);
} else {
    // Handle error if the prepared statement fails
    $total_pinjaman = 0;
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<?php include 'header.php' ?>
<!-- partial:partials/_sidebar.html -->
<?php include 'slidebar_anggota.php' ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-home"></i>
                    </span> Dashboard
                </h3>
            </div>
            <div class="row">
                <div class="col-md-6 stretch-card grid-margin">
                    <div class="card bg-gradient-danger card-img-holder text-white">
                        <div class="card-body">
                            <img src="../assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">Total Pinjaman <i class="mdi mdi-chart-line mdi-24px float-right"></i></h4>
                            <h2 class="mb-5">Rp. <?php echo number_format($total_pinjaman, 2); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 stretch-card grid-margin">
                    <div class="card bg-gradient-success card-img-holder text-white">
                        <div class="card-body">
                            <img src="../assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">Total Simpanan <i class="mdi mdi-chart-line mdi-24px float-right"></i></h4>
                            <h2 class="mb-5">
                                Rp. <?php echo $simpan_pokok !== null ? $simpan_pokok : "Not Available"; ?>
                            </h2>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "../public/footer.php" ?>
</html>