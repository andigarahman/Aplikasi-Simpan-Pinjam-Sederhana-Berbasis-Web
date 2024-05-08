<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pembayaran = $_POST['id_pembayaran'];
    $jumlah_bayar = filter_input(INPUT_POST, 'jumlah_bayar', FILTER_VALIDATE_INT);

    // Validasi Jumlah Bayar (if necessary)
    if ($jumlah_bayar === false || $jumlah_bayar < 0) {
        echo "Jumlah bayar tidak valid.";
        exit();
    }

    $user = "root";
    $pw = "";
    $db = "koperasi_sp";
    $host = "localhost";

    $conn = mysqli_connect($host, $user, $pw, $db);

    if ($_POST['action'] === 'accept') {
        // Terima pembayaran_angsuran
        $queryUpdate = "UPDATE pembayaran_angsuran SET status = 'diterima' WHERE id_pembayaran = ?";
        $stmt = mysqli_prepare($conn, $queryUpdate);
    
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $id_pembayaran);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
    
                // Dapatkan id_transaksi dari pembayaran_angsuran
                $queryGetTransaksi = "SELECT id_transaksi FROM pembayaran_angsuran WHERE id_pembayaran = ?";
                $stmtGetTransaksi = mysqli_prepare($conn, $queryGetTransaksi);
    
                if ($stmtGetTransaksi) {
                    mysqli_stmt_bind_param($stmtGetTransaksi, "s", $id_pembayaran);
                    mysqli_stmt_execute($stmtGetTransaksi);
                    mysqli_stmt_bind_result($stmtGetTransaksi, $id_transaksi);
    
                    if (mysqli_stmt_fetch($stmtGetTransaksi)) {
                        mysqli_stmt_close($stmtGetTransaksi);
    
                        // Kurangi jumlah_angsuran pada angsuran_berlangsung
                        $queryUpdateAngsuran = "UPDATE angsuran_berlangsung SET jumlah_angsuran = jumlah_angsuran - ? WHERE id_transaksi = ?";
                        $stmtAngsuran = mysqli_prepare($conn, $queryUpdateAngsuran);
    
                        if ($stmtAngsuran) {
                            mysqli_stmt_bind_param($stmtAngsuran, "ss", $jumlah_bayar, $id_transaksi);
                            mysqli_stmt_execute($stmtAngsuran);
                            mysqli_stmt_close($stmtAngsuran);
    
                            // Periksa apakah jumlah_angsuran sekarang menjadi 0
                            $queryCheckJumlahAngsuran = "SELECT jumlah_angsuran FROM angsuran_berlangsung WHERE id_transaksi = ?";
                            $stmtCheckJumlahAngsuran = mysqli_prepare($conn, $queryCheckJumlahAngsuran);
    
                            if ($stmtCheckJumlahAngsuran) {
                                mysqli_stmt_bind_param($stmtCheckJumlahAngsuran, "s", $id_transaksi);
                                mysqli_stmt_execute($stmtCheckJumlahAngsuran);
                                mysqli_stmt_bind_result($stmtCheckJumlahAngsuran, $new_jumlah_angsuran);
                                mysqli_stmt_fetch($stmtCheckJumlahAngsuran);
                                mysqli_stmt_close($stmtCheckJumlahAngsuran);
    
                                // Jika jumlah_angsuran sekarang menjadi 0, perbarui status menjadi "lunas"
                                if ($new_jumlah_angsuran == 0) {
                                    $queryUpdateStatusLunas = "UPDATE angsuran_berlangsung SET status = 'lunas' WHERE id_transaksi = ?";
                                    $stmtUpdateStatusLunas = mysqli_prepare($conn, $queryUpdateStatusLunas);
    
                                    if ($stmtUpdateStatusLunas) {
                                        mysqli_stmt_bind_param($stmtUpdateStatusLunas, "s", $id_transaksi);
                                        mysqli_stmt_execute($stmtUpdateStatusLunas);
                                        mysqli_stmt_close($stmtUpdateStatusLunas);
                                    } else {
                                        echo "Error updating status lunas: " . mysqli_error($conn);
                                    }
                                }
                            } else {
                                echo "Error checking jumlah_angsuran: " . mysqli_error($conn);
                            }
    
                            mysqli_close($conn);
                            header("Location: permintaan_pAngsuran.php");
                            exit();
                        } else {
                            echo "Error updating angsuran_berlangsung: " . mysqli_error($conn);
                            exit();
                        }
                    } else {
                        echo "Error fetching id_transaksi: " . mysqli_error($conn);
                        exit();
                    }
                } else {
                    echo "Error preparing statement: " . mysqli_error($conn);
                    exit();
                }
            } else {
                echo "Error executing statement: " . mysqli_stmt_error($stmt);
                exit();
            }
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pembayaran Angsuran</title>
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
                    <h4 class="card-title">Bayar Angsuran</h4>
                    <?php 
                    if (isset($_GET['success']) && $_GET['success'] == 1): 
                        echo "<p>Request simpan berhasil diproses.</p>";
                    endif; ?>
                    <p class="card-description">Masukan Data</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Request</th>
                                <th>Jumlah Transfer</th>
                                <th>Bukti Transfer</th>
                                <th>Action</th>
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

                        $sql = "SELECT * FROM pembayaran_angsuran WHERE status = 'menunggu'";
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
                                    <td><?php echo $row['jumlah_bayar']; ?></td>
                                    <td>
                                    <a href="../anggota/uploads/bukti_angsuran/<?php echo $row['bukti_transfer']; ?>" target="_blank">
                                        <img src="../anggota/uploads/bukti_angsuran/<?php echo $row['bukti_transfer']; ?>" alt="Bukti Transfer" style="max-width: 100px; max-height: 100px;">
                                    </a>
                                    </td>
                                    <td>
                                    <form class="forms-sample" method="post" action="permintaan_pAngsuran.php">
                                        <input type="hidden" name="id_pembayaran" value="<?php echo $row['id_pembayaran']; ?>">
                                        <?php if (isset($row['jumlah_bayar'])): ?>
                                            <p>Jumlah Bayar: <?php echo $row['jumlah_bayar']; ?></p>
                                            <input type="hidden" name="jumlah_bayar" value="<?php echo $row['jumlah_bayar']; ?>">
                                        <?php endif; ?>
                                        <button type="submit" name="action" value="accept">Terima</button>
                                        <button type="submit" name="action" value="reject">Tolak</button>
                                    </form>
                                    </td>
                                </tr>
                        <?php 
                            endforeach;
                        else: ?>
                            <tr>
                                <td colspan="4">Tidak ada request simpanan.</td>
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
