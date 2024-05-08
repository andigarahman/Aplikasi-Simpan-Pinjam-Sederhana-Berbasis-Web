<?php
include 'koneksi.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id_transaksi = $_GET['id'];

$queryGetData = "SELECT id_anggota, jumlah_peminjaman, lama_angsuran FROM permintaan_peminjaman WHERE id_transaksi = ?";
$stmtGetData = mysqli_prepare($conn, $queryGetData);

if ($stmtGetData) {
    mysqli_stmt_bind_param($stmtGetData, "s", $id_transaksi);
    mysqli_stmt_execute($stmtGetData);
    mysqli_stmt_bind_result($stmtGetData, $id_anggota, $jumlah_peminjaman, $lama_angsuran);
    mysqli_stmt_fetch($stmtGetData);
    mysqli_stmt_close($stmtGetData);
} else {
    echo "Error preparing statement to retrieve data: " . mysqli_error($conn);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $target_dir = __DIR__ . "/uploads/bukti_transfer/";
    $bukti_transfer = basename($_FILES["bukti_transfer"]["name"]);
    $target_file = $target_dir . $bukti_transfer;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["bukti_transfer"]["tmp_name"]);
    if ($check === false) {
        echo "File bukan gambar.";
        $uploadOk = 0;
    }

    if (file_exists($target_file)) {
        echo "Maaf, file sudah ada.";
        $uploadOk = 0;
    }

    if ($_FILES["bukti_transfer"]["size"] > 500000) {
        echo "Maaf, file terlalu besar.";
        $uploadOk = 0;
    }

    $allowed_formats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_formats)) {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Maaf, file Anda tidak diunggah.";
    } else {
        if (move_uploaded_file($_FILES["bukti_transfer"]["tmp_name"], $target_file)) {
            $queryUpdate = "UPDATE permintaan_peminjaman SET status = 'disetujui' WHERE id_transaksi = ?";
            $stmtUpdate = mysqli_prepare($conn, $queryUpdate);

            if ($stmtUpdate) {
                mysqli_stmt_bind_param($stmtUpdate, "s", $id_transaksi);
                if (mysqli_stmt_execute($stmtUpdate)) {
                    // Calculate jumlah_angsuran based on user input
                    $jumlah_peminjaman_input = $_POST['jumlah_peminjaman'];
                    $jumlah_angsuran = $jumlah_peminjaman_input * 1.01; // Adjust this calculation as needed

                    $queryInsertAngsuran = "INSERT INTO angsuran_berlangsung (id_transaksi, id_anggota, jumlah_angsuran, tanggal_disetujui, bukti_transfer, status, lama_angsuran, tenggat_waktu) VALUES (?, ?, ?, CURDATE(), ?, 'tidak_lunas', ?, DATE_ADD(CURDATE(), INTERVAL ? MONTH))";
                    $stmtAngsuran = mysqli_prepare($conn, $queryInsertAngsuran);

                    if ($stmtAngsuran) {
                        mysqli_stmt_bind_param($stmtAngsuran, "ssdssi", $id_transaksi, $id_anggota, $jumlah_angsuran, $bukti_transfer, $lama_angsuran, $lama_angsuran);

                        if (mysqli_stmt_execute($stmtAngsuran)) {
                            $queryInsertTransfer = "INSERT INTO transfer_peminjaman (id_transaksi, bukti_transfer) VALUES (?, ?)";
                            $stmtTransfer = mysqli_prepare($conn, $queryInsertTransfer);

                            if ($stmtTransfer) {
                                mysqli_stmt_bind_param($stmtTransfer, "ss", $id_transaksi, $bukti_transfer);

                                if (mysqli_stmt_execute($stmtTransfer)) {
                                    mysqli_stmt_close($stmtTransfer);
                                    mysqli_stmt_close($stmtAngsuran);
                                    mysqli_stmt_close($stmtUpdate);
                                    mysqli_close($conn);
                                    header("Location: ../admin/permintaan_pinjaman.php");
                                    exit();
                                } else {
                                    echo "Error executing insert statement into transfer_peminjaman: " . mysqli_stmt_error($stmtTransfer);
                                    mysqli_stmt_close($stmtTransfer);
                                }
                            } else {
                                echo "Error preparing insert statement into transfer_peminjaman: " . mysqli_error($conn);
                            }
                        } else {
                            echo "Error executing insert statement into angsuran_berlangsung: " . mysqli_stmt_error($stmtAngsuran);
                            mysqli_stmt_close($stmtAngsuran);
                        }
                    } else {
                        echo "Error preparing insert statement into angsuran_berlangsung: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error executing update statement: " . mysqli_stmt_error($stmtUpdate);
                    mysqli_stmt_close($stmtUpdate);
                }
            } else {
                echo "Error preparing update statement: " . mysqli_error($conn);
            }
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Upload Bukti Transfer</title>
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
    <div class="main-panel">
        <div class="content-wrapper">
            <h2>Upload Bukti Transfer</h2>

            <form class="forms-sample" method="post" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="jumlah_peminjaman">Jumlah Peminjaman:</label>
                    <input class="form-control" type="text" name="jumlah_peminjaman">
                </div>
                <div class="form-group">
                    <label for="bukti_transfer">Bukti Transfer (Gambar):</label>
                    <input class="form-control file-upload-info" type="file" name="bukti_transfer" accept="image/*" required>
                </div>
                <div class="form-group">
                    <button class="btn btn-gradient-primary me-2" type="submit" name="submit">Upload</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
