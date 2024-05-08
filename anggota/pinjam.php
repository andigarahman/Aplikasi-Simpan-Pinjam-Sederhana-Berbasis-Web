<!DOCTYPE html>
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id_anggota'])) {
    echo "Error: id_anggota is not set in the session.";
    exit();
}

$id_anggota = $_SESSION['id_anggota'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah_peminjaman = filter_input(INPUT_POST, 'jumlah_peminjaman', FILTER_VALIDATE_INT);
    $lama_angsuran = filter_input(INPUT_POST, 'lama_angsuran', FILTER_VALIDATE_INT);
    
    // Validate input values
    if ($jumlah_peminjaman === false || $jumlah_peminjaman <= 0 || $jumlah_peminjaman > 15000000) {
        echo '<p style="color: red;">Jumlah Maksimal Peminjaman Adalah Rp. 15.000.000 </p> <a href="pinjam.php" class="btn btn-primary">Kembali</a>';
        exit();
    }

    if ($lama_angsuran === false || !in_array($lama_angsuran, [10])) {
        echo "Lama angsuran tidak valid.";
        exit();
    }

    // File upload handling
    $syarat_peminjaman = '';

    if ($_FILES['syarat']['error'] === 0) {
        $target_dir = "uploads/bukti_transfer/";
        $syarat_peminjaman = $target_dir . basename($_FILES['syarat']['name']);

        if (!move_uploaded_file($_FILES['syarat']['tmp_name'], $syarat_peminjaman)) {
            echo "Error uploading file.";
            exit();
        }
    }

    // Database connection
    $koneksi = include '../control/koneksi.php';

    if (!$koneksi) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the anggota has angsuran berlangsung
    $queryCheckAngsuran = "SELECT * FROM angsuran_berlangsung WHERE id_anggota = ? AND status = 'tidak_lunas'";
    $stmtCheckAngsuran = mysqli_prepare($conn, $queryCheckAngsuran);

    if ($stmtCheckAngsuran) {
        mysqli_stmt_bind_param($stmtCheckAngsuran, "s", $id_anggota);
        mysqli_stmt_execute($stmtCheckAngsuran);
        mysqli_stmt_store_result($stmtCheckAngsuran);

        // Jika ada angsuran berlangsung, berikan pesan dan tampilkan tabel
        if (mysqli_stmt_num_rows($stmtCheckAngsuran) > 0) {
            echo '<div class="main-panel">';
            echo '<p style="color: red;">Anda tidak bisa meminjam sekarang, karena masih memiliki angsuran yang sedang berlangsung.</p>';
            
            // Tampilkan tabel angsuran berlangsung
            echo '<h4>Angsuran Berlangsung</h4>';
            echo '<table border="1">
                    <tr>
                        <th>ID Angsuran</th>
                        <th>ID Transaksi</th>
                        <th>Jumlah Angsuran</th>
                        <th>Tanggal Disetujui</th>
                        <th>Status</th>
                        <th>Lama Angsuran</th>
                        <th>Tenggat Waktu</th>
                    </tr>';

            mysqli_stmt_bind_result($stmtCheckAngsuran, $id_angsuran, $id_transaksi, $id_anggota_angsuran, $jumlah_angsuran, $tanggal_disetujui, $bukti_transfer, $status, $lama_angsuran, $tenggat_waktu);

            while (mysqli_stmt_fetch($stmtCheckAngsuran)) {
                echo '<tr>
                        <td>' . $id_angsuran . '</td>
                        <td>' . $id_transaksi . '</td>
                        <td>' . $jumlah_angsuran . '</td>
                        <td>' . $tanggal_disetujui . '</td>
                        <td>' . $status . '</td>
                        <td>' . $lama_angsuran . '</td>
                        <td>' . $tenggat_waktu . '</td>
                    </tr>';
            }

            echo '</table>';
            echo '<br>';
            echo '<a href="pinjaman.php" class="btn btn-primary">Lihat Angsuran Berlangsung</a>';
            echo '</div >';
            mysqli_stmt_close($stmtCheckAngsuran);

            // Keluar dari script
            mysqli_close($conn);
            exit();
        }

        mysqli_stmt_close($stmtCheckAngsuran);
    } else {
        echo "Error preparing check angsuran statement: " . mysqli_error($conn);
        mysqli_close($conn);
        exit();
    }

    $queryInsert = "INSERT INTO permintaan_peminjaman (id_anggota, jumlah_peminjaman, lama_angsuran, syarat_peminjaman) 
                    VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $queryInsert);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "siss",
            $id_anggota,
            $jumlah_peminjaman,
            $lama_angsuran,
            $syarat_peminjaman
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            header("Location: status_permintaan_pinjamann.php");
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
    <title>Pinjam</title>
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
                    
                        <h4>Form Peminjaman</h4>
                        <br>
                        <form class="forms-sample" method="post" action="pinjam.php" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="jumlah_peminjaman">Jumlah Peminjaman (max 15jt) :</label>
                                <input type="text" class="form-control" name="jumlah_peminjaman" required>
                            </div>
                            <div class="form-group">
                                <label for="lama_angsuran">Lama Angsuran:</label>
                                <select class="form-control" name="lama_angsuran" required>
                                    <option value="10" selected>10 Bulan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="syarat">Upload Syarat Peminjaman (PDF) :</label>
                                <input class="form-control file-upload-info" type="file" class="form-control-file" name="syarat" accept=".pdf" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "../public/footer.php" ?>
</body>         
</html>
