<!-- File: delete_anggota.php -->

<?php
include 'koneksi.php';

// Get the member's ID from the URL
$id_anggota = $_GET['id'];

// Query to delete member
$query_delete_anggota = "DELETE FROM anggota WHERE id_anggota = '$id_anggota'";
$result_delete_anggota = mysqli_query($conn, $query_delete_anggota);

// Check the result
if ($result_delete_anggota) {
    echo "Data anggota berhasil dihapus.";
} else {
    echo "Error deleting data: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
