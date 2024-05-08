<?php

    $user = "root";
    $pw = "";
    $db = "koperasi_sp";
    $host = "localhost";

    $conn = mysqli_connect($host, $user, $pw, $db);

    if(!$conn){
        die("Koneksi database gagal ".mysqli_connect_error());
    } 

?>