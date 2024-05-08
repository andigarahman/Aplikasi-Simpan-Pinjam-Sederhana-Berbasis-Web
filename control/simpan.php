<?php

// simpan.php anggota
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah_transfer = $_POST['jumlah_transfer'];
    $bukti_transfer = $_FILES['img']['name'];

    // Validasi File Upload - Pastikan itu adalah gambar
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
    $file_extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);

    if (!in_array($file_extension, $allowed_extensions)) {
        echo "File harus berupa gambar (JPG, JPEG, PNG, GIF)";
        exit();
    }

    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $target_file = $upload_dir . basename($bukti_transfer);
    move_uploaded_file($_FILES['img']['tmp_name'], $target_file);

    // Check if id_anggota is set in the session and is a numeric value
    if (isset($_SESSION['id_anggota']) && is_numeric($_SESSION['id_anggota'])) {
        $id_anggota = $_SESSION['id_anggota'];

        $koneksi = include 'koneksi.php';

        if (!$koneksi) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Prepared Statement untuk melindungi dari SQL Injection
        $queryInsert = "INSERT INTO transaksi_simpan (id_anggota, jumlah_transfer, bukti_transfer, status) VALUES (?, ?, ?, 'menunggu')";
        $stmtInsert = mysqli_prepare($koneksi, $queryInsert);

        if ($stmtInsert) {
            mysqli_stmt_bind_param($stmtInsert, "iss", $id_anggota, $jumlah_transfer, $bukti_transfer);

            if (mysqli_stmt_execute($stmtInsert)) {
                // Update anggota
                $queryUpdate = "UPDATE anggota SET simpan_pokok = simpan_pokok + ? WHERE id_anggota = ?";
                $stmtUpdate = mysqli_prepare($koneksi, $queryUpdate);

                if ($stmtUpdate) {
                    mysqli_stmt_bind_param($stmtUpdate, "ss", $jumlah_transfer, $id_anggota);

                    if (mysqli_stmt_execute($stmtUpdate)) {
                        mysqli_stmt_close($stmtUpdate);
                        mysqli_close($koneksi);

                        header("Location: request_simpan.php?success=1");
                        exit();
                    } else {
                        echo "Error updating anggota: " . mysqli_stmt_error($stmtUpdate);
                    }
                } else {
                    echo "Error preparing update statement: " . mysqli_error($koneksi);
                }
            } else {
                echo "Error inserting data: " . mysqli_stmt_error($stmtInsert);
            }

            mysqli_stmt_close($stmtInsert);
        } else {
            echo "Error preparing insert statement: " . mysqli_error($koneksi);
        }

        mysqli_close($koneksi);
    } else {
        echo "Error: Invalid or missing id_anggota in the session.";
    }
}
?>
