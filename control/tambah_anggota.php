<?php

include 'koneksi.php';

// Tangkap data dari formulir
$id_anggota = $_POST['id_anggota'];
$nama_anggota = $_POST['nama_anggota'];
$gender = $_POST['gender'];
$alamat = $_POST['alamat'];
$no_tlp = $_POST['no_tlp'];
$password = md5($_POST['password']); // Gunakan MD5 untuk mengenkripsi password
$simpanan = $_POST['simpanan'];

// Query untuk menambahkan data ke tabel anggota
$query_insert_anggota = "INSERT INTO anggota (id_anggota, nama_anggota, gender, alamat, no_tlp, password, simpan_pokok) VALUES ('$id_anggota', '$nama_anggota', '$gender', '$alamat', '$no_tlp', '$password', $simpanan)";

// Eksekusi query
$result_insert_anggota = mysqli_query($conn, $query_insert_anggota);

// Periksa hasil eksekusi query
if ($result_insert_anggota) {
    echo '<p style="color: green;">Data Anggota baru berhasil disimpan!</p>';
} else {
    echo "Error: " . mysqli_error($conn);
}

// Tutup koneksi
mysqli_close($conn);
?>
