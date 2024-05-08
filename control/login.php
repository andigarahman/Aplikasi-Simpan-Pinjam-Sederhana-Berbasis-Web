<?php
// Start the session
session_start();

include "koneksi.php";

function redirectToDashboard($role) {
    if ($role == 'admin') {
        header("location: ../admin/dashboard_admin.php");
    } elseif ($role == 'anggota') {
        header("location: ../anggota/dashboard_anggota.php");
    } elseif ($role == 'bendahara') {
        header("location: ../bendahara/dashboard_bendahara.php");
    } else {
        // Handle unknown role
        header("location: index.php?pesan=gagal");
    }
    exit(); // Ensure script stops after redirection
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $password = $_POST['password'];

    // Enkripsi password menggunakan hash MD5 (harap diperhatikan: MD5 tidak disarankan untuk keamanan yang tinggi)
    $hashed_password = md5($password);

    // Array of table names
    $tables = ['admin', 'anggota', 'bendahara'];
    $id_columns = ['id_admin', 'id_anggota', 'id_bendahara'];
    $nama_columns = ['nama_admin', 'nama_anggota', 'nama_bendahara'];

    // Check each table
    foreach ($tables as $index => $table) {
        $id_column = $id_columns[$index];
        $nama_column = $nama_columns[$index];

        $query = mysqli_query($conn, "SELECT * FROM $table WHERE $id_column = '$id' AND password = '$hashed_password'");

        // Check if the query was successful
        if (!$query) {
            die('Error in the query: ' . mysqli_error($conn));
        }

        $jumlah_data = mysqli_num_rows($query);

        if ($jumlah_data > 0) {
            $res = mysqli_fetch_array($query);

            // Session
            $_SESSION['username'] = $id;
            $_SESSION['nama'] = $res[$nama_column];

            // Set id_anggota in the session for 'anggota' table
            if ($table == 'anggota') {
                $_SESSION['id_anggota'] = $id;
            }

            // Redirect based on the table
            redirectToDashboard($table);
        }
    }

    // If no valid login found, redirect to index.php with an error message
    header("location: index.php?pesan=gagal");
}
?>
