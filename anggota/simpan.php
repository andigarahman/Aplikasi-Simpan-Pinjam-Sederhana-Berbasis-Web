<!DOCTYPE html>
<?php
session_start();

// Validate if id_anggota is set in the session
if (!isset($_SESSION['id_anggota'])) {
    echo "Error: id_anggota is not set in the session.";
    exit();
}

$id_anggota = $_SESSION['id_anggota'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah_transfer = filter_input(INPUT_POST, 'jumlah_transfer', FILTER_VALIDATE_INT);
    $bukti_transfer = $_FILES['img']['name'];

    // Validate jumlah_transfer
    if ($jumlah_transfer === false || $jumlah_transfer < 0) {
        echo "Jumlah transfer tidak valid.";
        exit();
    }

    // ... (File upload validation and handling)

    // Define the upload directory
    $upload_dir = "uploads/";

    // Check if the directory exists, if not, create it
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Define the full path for the uploaded file
    $upload_file = $upload_dir . basename($bukti_transfer);

    // ... (continue with your code)

    $koneksi = include '../control/koneksi.php';

    if (!$koneksi) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $queryInsert = "INSERT INTO transaksi_simpan (id_anggota, jumlah_transfer, bukti_transfer, status) VALUES (?, ?, ?, 'menunggu')";

    $stmt = mysqli_prepare($conn, $queryInsert);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $id_anggota, $jumlah_transfer, $bukti_transfer);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            move_uploaded_file($_FILES['img']['tmp_name'], $upload_file); // Move the uploaded file to the specified directory
            
            header("Location: transaksi_simpan_saya.php");
            exit();
        } else {
            echo "Error executing insert statement: " . mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            exit();
        }
    } else {
        echo "Error preparing insert statement: " . mysqli_error($conn);
        mysqli_close($conn);
        exit();
    }
}
?>

<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Simpan</title>
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

<?php include 'header.php' ?>
<?php include 'slidebar_anggota.php' ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Form Simpan</h4>
                    <p class="card-description"> Masukan Data </p>
                    <form class="forms-sample" method="post" action="simpan.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="jumlah_transfer">Jumlah Transfer :</label>
                            <input type="text" class="form-control" name="jumlah_transfer" placeholder="Tuliskan Jumlahnya" required>
                        </div>
                        <div class="form-group">
                            <label>File upload</label>
                            <input type="file" name="img" class="file-upload-default">
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image" required>
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                                </span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                        <button class="btn btn-light">Cancel</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../public/footer.php" ?>

</html>
