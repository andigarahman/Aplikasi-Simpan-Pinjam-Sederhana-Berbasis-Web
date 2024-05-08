<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>
<!DOCTYPE html>

<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Riwayat Simpan</title>
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

<?php include 'header.php'; ?>
<?php include 'slidebar_admin.php'; ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Riwayat Data Simpan</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Request</th>
                                <th>ID Anggota</th>
                                <th>Jumlah Transfer</th>
                                <th>Bukti Transfer</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        // Fetch data from the database
                        $user = "root";
                        $pw = "";
                        $db = "koperasi_sp";
                        $host = "localhost";

                        $conn = mysqli_connect($host, $user, $pw, $db);

                        if (!$conn) {
                            die("Koneksi database gagal " . mysqli_connect_error());
                        }

                        $sql = "SELECT * FROM transaksi_simpan";
                        $result = mysqli_query($conn, $sql);

                        $request_simpan_data = array();

                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                $request_simpan_data[] = $row;
                            }
                        }

                        mysqli_close($conn);

                        if (!empty($request_simpan_data)): 
                            foreach ($request_simpan_data as $row): ?>
                                <tr>
                                    <td><?php echo $row['id_transaksi']; ?></td>
                                    <td><?php echo $row['id_anggota']; ?></td>
                                    <td><?php echo $row['jumlah_transfer']; ?></td>
                                    <td>
                                        <a href="../anggota/uploads/<?php echo $row['bukti_transfer']; ?>" target="_blank">
                                            <img src="../anggota/uploads/<?php echo $row['bukti_transfer']; ?>" alt="Bukti Transfer" style="max-width: 100px; max-height: 100px;">
                                        </a>
                                    </td>
                                    <td><?php echo $row['status']; ?></td>
                                </tr>
                        <?php 
                            endforeach;
                        else: ?>
                            <tr>
                                <td colspan="7">Tidak ada data transaksi simpanan.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../public/footer.php"; ?>

</html>
