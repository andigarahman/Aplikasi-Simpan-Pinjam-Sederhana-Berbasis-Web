<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_request = $_POST['id_request'];
    $jumlah_transfer = filter_input(INPUT_POST, 'jumlah_transfer', FILTER_VALIDATE_INT);

    // Validasi Jumlah Transfer (if necessary)
    if ($jumlah_transfer === false || $jumlah_transfer < 0) {
        echo "Jumlah transfer tidak valid.";
        exit();
    }

    $user = "root";
    $pw = "";
    $db = "koperasi_sp";
    $host = "localhost";

    $conn = mysqli_connect($host, $user, $pw, $db);

    if (!$conn) {
        die("Koneksi database gagal " . mysqli_connect_error());
    }

    // Prepared Statement untuk melindungi dari SQL Injection
    if ($_POST['action'] === 'accept') {
        // Terima simpanan
        $queryUpdate = "UPDATE transaksi_simpan SET status = 'diterima', jumlah_diterima = ? WHERE id_transaksi = ?";
        $stmt = mysqli_prepare($conn, $queryUpdate);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $jumlah_transfer, $id_request);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                // Update simpan_pokok in anggota table
                $queryUpdateSimpanPokok = "UPDATE anggota SET simpan_pokok = simpan_pokok + ? WHERE id_anggota IN (SELECT id_anggota FROM transaksi_simpan WHERE id_transaksi = ?)";
                $stmtSimpanPokok = mysqli_prepare($conn, $queryUpdateSimpanPokok);

                if ($stmtSimpanPokok) {
                    mysqli_stmt_bind_param($stmtSimpanPokok, "ss", $jumlah_transfer, $id_request);
                    mysqli_stmt_execute($stmtSimpanPokok);
                    mysqli_stmt_close($stmtSimpanPokok);

                    mysqli_close($conn);
                    header("Location: permintaan_simpan.php");
                    exit();
                } else {
                    echo "Error updating simpan_pokok: " . mysqli_error($conn);
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
    } elseif ($_POST['action'] === 'reject') {
        // Tolak simpanan dan set jumlah_diterima menjadi 0
        $queryUpdate = "UPDATE transaksi_simpan SET status = 'ditolak', jumlah_diterima = 0 WHERE id_transaksi = ?";
        $stmt = mysqli_prepare($conn, $queryUpdate);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $id_request);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header("Location: permintaan_simpan.php");
                exit();
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

<!DOCTYPE html>


<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Permintaan Simpan</title>
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
                    <h4 class="card-title">Request Simpan</h4>
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

                        $sql = "SELECT * FROM transaksi_simpan WHERE status = 'menunggu'";
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
                                    <td><?php echo $row['jumlah_transfer']; ?></td>
                                    <td>
                                    <a href="../anggota/uploads/<?php echo $row['bukti_transfer']; ?>" target="_blank">
                                        <img src="../anggota/uploads/<?php echo $row['bukti_transfer']; ?>" alt="Bukti Transfer" style="max-width: 100px; max-height: 100px;">
                                    </a>
                                    </td>
                                    <td>
                                        <form class="forms-sample" method="post" action="permintaan_simpan.php">
                                            <input type="hidden" name="id_request" value="<?php echo $row['id_transaksi']; ?>">
                                            <p>Jumlah Diterima: <?php echo $row['jumlah_transfer']; ?></p>
                                            <input type="hidden" name="jumlah_transfer" value="<?php echo $row['jumlah_transfer']; ?>">
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
