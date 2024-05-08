<?php
// Database connection
$koneksi = include 'koneksi.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get ID from the query string
$id_transaksi = $_GET['id'];

// Update status to 'ditolak'
$queryUpdate = "UPDATE permintaan_peminjaman SET status = 'ditolak' WHERE id_transaksi = ?";
$stmt = mysqli_prepare($conn, $queryUpdate);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $id_transaksi);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: ../admin/permintaan_pinjaman.php");
        exit();
    } else {
        echo "Error executing update statement: " . mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        exit();
    }
} else {
    echo "Error preparing update statement: " . mysqli_error($conn);
    mysqli_close($conn);
    exit();
}
?>
