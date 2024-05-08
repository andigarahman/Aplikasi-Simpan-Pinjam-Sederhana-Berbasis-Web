<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_request = $_POST['id_request'];
    $jumlah_diterima = mysqli_real_escape_string($_POST['jumlah_diterima']);

    // Validasi Jumlah Diterima
    if (!is_numeric($jumlah_diterima) || $jumlah_diterima < 0) {
        // Jumlah diterima tidak valid, Anda dapat menangani kesalahan di sini
        echo "Jumlah diterima tidak valid.";
        exit();
    }

    $koneksi = include 'koneksi.php';

    // Prepared Statement untuk melindungi dari SQL Injection
    $queryUpdate = "UPDATE transaksi_simpan SET status = 'diterima', jumlah_diterima = ? WHERE id_transaksi = ?";
    $stmt = mysqli_prepare($koneksi, $queryUpdate);
    mysqli_stmt_bind_param($stmt, "ss", $jumlah_diterima, $id_request);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_close($koneksi);

    header("Location: request_simpan.php?success=1");
    exit();
}
?>
