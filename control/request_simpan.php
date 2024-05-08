<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_request = $_POST['id_request'];
    $jumlah_diterima = filter_input(INPUT_POST, 'jumlah_diterima', FILTER_VALIDATE_INT);

    // Validasi Jumlah Diterima
    if ($jumlah_diterima === false || $jumlah_diterima < 0) {
        // Jumlah diterima tidak valid, Anda dapat menangani kesalahan di sini
        echo "Jumlah diterima tidak valid.";
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
    if ($jumlah_diterima > 0) {
        // Terima simpanan
        $queryUpdate = "UPDATE transaksi_simpan SET status = 'diterima', jumlah_diterima = ? WHERE id_transaksi = ?";
        $stmt = mysqli_prepare($conn, $queryUpdate);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $jumlah_diterima, $id_request);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
            } else {
                echo "Error executing statement: " . mysqli_stmt_error($stmt);
            }
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
        }
    } else {
        // Tolak simpanan
        $queryUpdate = "UPDATE transaksi_simpan SET status = 'ditolak' WHERE id_transaksi = ?";
        $stmt = mysqli_prepare($conn, $queryUpdate);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $id_request);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
            } else {
                echo "Error executing statement: " . mysqli_stmt_error($stmt);
            }
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);

    header("Location: permintaan_simpan.php?success=1");
    exit();
}
?>
