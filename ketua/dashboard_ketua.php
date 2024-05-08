<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">
<?php include 'header.php' ?>
<!-- partial:partials/_sidebar.html -->
<?php include 'slidebar_ketua.php' ?>
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
                <?php
                    include '../control/koneksi.php';

                    // Query to get the total number of members
                    $query_total_members = "SELECT COUNT(*) AS total_members FROM anggota";
                    $result_total_members = mysqli_query($conn, $query_total_members);

                    // Query to get the total sum of simpan_pokok
                    $query_total_simpanan = "SELECT SUM(simpan_pokok) AS total_simpanan FROM anggota";
                    $result_total_simpanan = mysqli_query($conn, $query_total_simpanan);

                    // Query to get the total sum of jumlah_angsuran from angsuran_berlangsung
                    $query_total_angsuran = "SELECT SUM(jumlah_angsuran) AS total_angsuran FROM angsuran_berlangsung";
                    $result_total_angsuran = mysqli_query($conn, $query_total_angsuran);

                    // Fetch the results
                    $row_total_members = mysqli_fetch_assoc($result_total_members);
                    $row_total_simpanan = mysqli_fetch_assoc($result_total_simpanan);
                    $row_total_angsuran = mysqli_fetch_assoc($result_total_angsuran);

                    // Close the database connection
                    mysqli_close($conn);

                    // Display the results
                    $total_members = $row_total_members['total_members'];
                    $total_simpanan = $row_total_simpanan['total_simpanan'];
                    $total_angsuran = $row_total_angsuran['total_angsuran'];
                    ?>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                            <img src="../assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">Total Dipinjam <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5"><?php echo $total_angsuran; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                            <img src="../assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">Total Simpanan <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5"><?php echo $total_simpanan; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                        <div class="card-body">
                            <img src="../assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">Total Member <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5"><?php echo $total_members; ?></h2>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "../public/footer.php" ?>
</html>
