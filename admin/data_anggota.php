<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Data Anggota</title>
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
<?php include 'header.php' ?>
<!-- partial:partials/_sidebar.html -->
<?php include 'slidebar_admin.php' ?>
          <div class="main-panel">
              <div class="content-wrapper">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">List Anggota</h4>
                        <p class="card-description">
                        </p>
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                                <th> ID Anggota </th>
                                <th> Nama Anggota </th>
                                <th> Jenis Kelamin </th>
                                <th> Alamat </th>
                                <th> No. Telp </th>
                                <th> Simpan </th>
                                <th> Action </th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $user = "root";
                            $pw = "";
                            $db = "koperasi_sp";
                            $host = "localhost";
                        
                            $koneksi = mysqli_connect($host, $user, $pw, $db);
                        
                            if(!$koneksi){
                                die("Koneksi database gagal ".mysqli_connect_error());
                            } 

                            // Query untuk mendapatkan data anggota
                            $query = "SELECT id_anggota, nama_anggota, gender, alamat, no_tlp, simpan_pokok FROM anggota";
                            $result = mysqli_query($koneksi, $query);
                            $data = array();

                            while ($row = mysqli_fetch_assoc($result)) {
                                array_unshift($data, $row);
                            }

                            foreach ($data as $row) {
                                echo "<tr>";
                                echo "<td>" . $row['id_anggota'] . "</td>";
                                echo "<td>" . $row['nama_anggota'] . "</td>";
                                echo "<td>" . $row['gender'] . "</td>";
                                echo "<td>" . $row['alamat'] . "</td>";
                                echo "<td>" . $row['no_tlp'] . "</td>";
                                echo "<td>" . $row['simpan_pokok'] . "</td>";
                                echo "<td> <a href='edit_anggota.php?id=" . $row['id_anggota'] . "'>Edit</a> 
                                          | 
                                          <a href='../control/delete_anggota.php?id=" . $row['id_anggota'] . "'>Delete</a> </td>";
                                echo "</tr>";
                            }

                            // Bebaskan hasil query
                            mysqli_free_result($result);

                            // Tutup koneksi
                            mysqli_close($koneksi);

                            ?>

                        </tbody>
                        </table>
                      </div>
                    </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <?php include "../public/footer.php" ?>
</html>