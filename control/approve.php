<?php
// Database connection
include 'koneksi.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get ID from the query string
$id_transaksi = $_GET['id'];

// Process form submission when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload
    $target_dir = __DIR__ . "/uploads/bukti_transfer/";
    $bukti_transfer = basename($_FILES["bukti_transfer"]["name"]);
    $target_file = $target_dir . $bukti_transfer;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a valid image
    $check = getimagesize($_FILES["bukti_transfer"]["tmp_name"]);
    if ($check === false) {
        echo "File bukan gambar.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Maaf, file sudah ada.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["bukti_transfer"]["size"] > 500000) {
        echo "Maaf, file terlalu besar.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowed_formats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_formats)) {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Maaf, file Anda tidak diunggah.";
    } else {
        // Try to upload file
        if (move_uploaded_file($_FILES["bukti_transfer"]["tmp_name"], $target_file)) {
            // Update status and bukti_transfer in the database
            $queryUpdate = "UPDATE permintaan_peminjaman SET status = 'disetujui' WHERE id_transaksi = ?";
            $stmt = mysqli_prepare($conn, $queryUpdate);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $id_transaksi);

                if (mysqli_stmt_execute($stmt)) {
                    // Retrieve id_anggota, jumlah_angsuran, and lama_angsuran from permintaan_pinjaman table
                    $queryGetData = "SELECT id_anggota, jumlah_peminjaman, lama_angsuran FROM permintaan_peminjaman WHERE id_transaksi = ?";
                    $stmtGetData = mysqli_prepare($conn, $queryGetData);

                    if ($stmtGetData) {
                        mysqli_stmt_bind_param($stmtGetData, "s", $id_transaksi);
                        mysqli_stmt_execute($stmtGetData);
                        mysqli_stmt_bind_result($stmtGetData, $id_anggota, $jumlah_angsuran, $lama_angsuran);
                        mysqli_stmt_fetch($stmtGetData);
                        mysqli_stmt_close($stmtGetData);

                        // Insert data into angsuran_berlangsung table
                        $queryInsertAngsuran = "INSERT INTO angsuran_berlangsung (id_transaksi, id_anggota, jumlah_angsuran, tanggal_disetujui, bukti_transfer, status, lama_angsuran, tenggat_waktu) VALUES (?, ?, ?, CURDATE(), ?, 'tidak_lunas', ?, DATE_ADD(CURDATE(), INTERVAL ? MONTH))";
                        $stmtAngsuran = mysqli_prepare($conn, $queryInsertAngsuran);

                        if ($stmtAngsuran) {
                            mysqli_stmt_bind_param($stmtAngsuran, "ssssii", $id_transaksi, $id_anggota, $jumlah_angsuran, $bukti_transfer, $lama_angsuran, $lama_angsuran);

                            if (mysqli_stmt_execute($stmtAngsuran)) {
                                // Insert data into transfer_peminjaman table
                                $queryInsertTransfer = "INSERT INTO transfer_peminjaman (id_transaksi, bukti_transfer) VALUES (?, ?)";
                                $stmtTransfer = mysqli_prepare($conn, $queryInsertTransfer);

                                if ($stmtTransfer) {
                                    mysqli_stmt_bind_param($stmtTransfer, "ss", $id_transaksi, $bukti_transfer);

                                    if (mysqli_stmt_execute($stmtTransfer)) {
                                        mysqli_stmt_close($stmtTransfer);
                                        mysqli_stmt_close($stmtAngsuran);
                                        mysqli_stmt_close($stmt);
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
                        echo "Error preparing statement to retrieve data: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error executing update statement: " . mysqli_stmt_error($stmt);
                    mysqli_stmt_close($stmt);
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
