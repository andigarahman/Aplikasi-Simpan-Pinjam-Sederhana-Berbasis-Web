<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Bayar Angsuran</title>
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

                        <h3>Form Pembayaran Angsuran</h3>
                        <br>
                        <?php
                        include "../control/koneksi.php";

                        // Check if the form is submitted
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Retrieve data from angsuran_berlangsung table
                            $id_transaksi_param = isset($_POST['id_transaksi']) ? $_POST['id_transaksi'] : null;

                            if ($id_transaksi_param) {
                                $queryGetAngsuran = "SELECT jumlah_angsuran, lama_angsuran FROM angsuran_berlangsung WHERE id_transaksi = ?";
                                $stmtGetAngsuran = mysqli_prepare($conn, $queryGetAngsuran);

                                if ($stmtGetAngsuran) {
                                    mysqli_stmt_bind_param($stmtGetAngsuran, "s", $id_transaksi_param);
                                    mysqli_stmt_execute($stmtGetAngsuran);
                                    mysqli_stmt_bind_result($stmtGetAngsuran, $jumlah_angsuran, $lama_angsuran);
                                    mysqli_stmt_fetch($stmtGetAngsuran);
                                    mysqli_stmt_close($stmtGetAngsuran);

                                    // Calculate the minimum payment
                                    $min_payment = ($lama_angsuran != 0) ? ceil($jumlah_angsuran / $lama_angsuran) : 0;

                                    // Set $jumlah_bayar from the form
                                    $jumlah_bayar = isset($_POST['jumlah_bayar']) ? $_POST['jumlah_bayar'] : 0;

                                    // Validate input values
                                    if ($jumlah_bayar < $min_payment || $jumlah_bayar > $jumlah_angsuran) {
                                        echo '<p style="color: red;">Jumlah pembayaran tidak valid. Minimal pembayaran adalah ' . $min_payment . ' dan maksimal pembayaran adalah ' . $jumlah_angsuran . '</p>';
                                    } else {
                                        // Set $bukti_transfer from the form
                                        $bukti_transfer = isset($_FILES['bukti_transfer']['name']) ? $_FILES['bukti_transfer']['name'] : '';
                                        $bukti_transfer_temp = isset($_FILES['bukti_transfer']['tmp_name']) ? $_FILES['bukti_transfer']['tmp_name'] : '';

                                        if (!empty($bukti_transfer_temp) && !empty($bukti_transfer)) {
                                            // Move the uploaded file to the desired directory
                                            move_uploaded_file($bukti_transfer_temp, "uploads/bukti_angsuran/$bukti_transfer");

                                            // Set $status to 'menunggu'
                                            $status = 'menunggu';

                                            // Insert data into the 'pembayaran_angsuran' table
                                            $queryInsert = "INSERT INTO pembayaran_angsuran (id_transaksi, jumlah_bayar, bukti_transfer, tanggal_pembayaran, status) VALUES (?, ?, ?, CURDATE(), 'menunggu')";
                                            $stmtInsert = mysqli_prepare($conn, $queryInsert);

                                            if ($stmtInsert) {
                                                // Change the parameter type to "sds"
                                                mysqli_stmt_bind_param($stmtInsert, "sds", $id_transaksi_param, $jumlah_bayar, $bukti_transfer);

                                                if (mysqli_stmt_execute($stmtInsert)) {
                                                    mysqli_stmt_close($stmtInsert);
                                                    echo '<p style="color: green;">Data pembayaran berhasil disimpan!</p>';
                                                } else {
                                                    echo '<p style="color: red;">Error executing the statement: ' . mysqli_error($conn) . '</p>';
                                                }
                                            } else {
                                                echo '<p style="color: red;">Error preparing the statement: ' . mysqli_error($conn) . '</p>';
                                            }
                                        } else {
                                            echo '<p style="color: red;">Error uploading bukti transfer.</p>';
                                        }
                                    }
                                } else {
                                    echo '<p style="color: red;">Error preparing the statement to retrieve data: ' . mysqli_error($conn) . '</p>';
                                }
                            } else {
                                echo '<p style="color: red;">ID transaksi tidak valid.</p>';
                            }
                        }
                        ?>

                        <form class="forms-sample" method="post" action="pembayaran_angsuran_test.php?id_transaksi=<?php echo isset($_GET['id_transaksi']) ? $_GET['id_transaksi'] : ''; ?>" enctype="multipart/form-data">
                            <!-- Tambahkan input tersembunyi untuk menyimpan id_transaksi -->
                            <!-- Saya asumsikan bahwa Anda mendapatkan id_transaksi dari data pinjaman yang ditampilkan sebelumnya -->
                            <?php
                            if (isset($_GET['id_transaksi'])) {
                                $id_transaksi_param = $_GET['id_transaksi'];
                                echo "<input type='hidden' name='id_transaksi' value='" . $id_transaksi_param . "'>";
                            }
                            ?>

                            <div class="form-group">
                                <label for="jumlah_bayar">Jumlah Pembayaran (Minimal <?php echo isset($min_payment) ? $min_payment : ''; ?>):</label>
                                <input class="form-control" type="number" name="jumlah_bayar" min="<?php echo isset($min_payment) ? $min_payment : 1; ?>" max="<?php echo $jumlah_angsuran; ?>" step="1" required>
                            </div>
                            <div class="form-group">
                                <label for="bukti_transfer">Bukti Transfer (Gambar):</label>
                                <input class="form-control file-upload-info" type="file" name="bukti_transfer" accept="image/*" required>
                            </div>
                            <button class="btn btn-gradient-primary me-2" type="submit">Bayar Angsuran</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "../public/footer.php" ?>
</body>

</html>
