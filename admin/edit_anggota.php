<!DOCTYPE html>
<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = "root";
    $pw = "";
    $db = "koperasi_sp";
    $host = "localhost";

    $koneksi = mysqli_connect($host, $user, $pw, $db);

    if (!$koneksi) {
        die("Koneksi database gagal " . mysqli_connect_error());
    }

    // Get data from the form
    $id_anggota = $_POST['id_anggota'];
    $nama_anggota = $_POST['nama_anggota'];
    $gender = $_POST['gender'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];
    $simpan_pokok = $_POST['simpan_pokok'];

    // Update the data in the database
    $query = "UPDATE anggota 
              SET nama_anggota = '$nama_anggota', 
                  gender = '$gender', 
                  alamat = '$alamat', 
                  no_tlp = '$no_tlp', 
                  simpan_pokok = '$simpan_pokok' 
              WHERE id_anggota = '$id_anggota'";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: data_anggota.php");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);
} else {
    // Display the form to edit member information
    $id_anggota = $_GET['id'];
    $query = "SELECT * FROM anggota WHERE id_anggota = '$id_anggota'";

    $user = "root";
    $pw = "";
    $db = "koperasi_sp";
    $host = "localhost";

    $koneksi = mysqli_connect($host, $user, $pw, $db);

    if (!$koneksi) {
        die("Koneksi database gagal " . mysqli_connect_error());
    }

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);
}
?>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Data Anggota</title>
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

<?php include 'header.php'; ?>
<?php include 'slidebar_admin.php'; ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Data Anggota</h4>
                    <form class="forms-sample" method="post" action="">
                        <!-- Hidden input field to store the member ID -->
                        <input type="hidden" name="id_anggota" value="<?php echo $row['id_anggota']; ?>">

                        <div class="form-group">
                            <label for="nama_anggota">Nama Anggota</label>
                            <input type="text" class="form-control" id="nama_anggota" name="nama_anggota" value="<?php echo $row['nama_anggota']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="gender">Jenis Kelamin</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="pria" <?php if ($row['gender'] == 'pria') echo 'selected="selected"'; ?>>Pria</option>
                                <option value="wanita" <?php if ($row['gender'] == 'wanita') echo 'selected="selected"'; ?>>Wanita</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat"><?php echo $row['alamat']; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="no_tlp">No. Telp</label>
                            <input type="text" class="form-control" id="no_tlp" name="no_tlp" value="<?php echo $row['no_tlp']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="simpan_pokok">Simpan Pokok</label>
                            <input type="text" class="form-control" id="simpan_pokok" name="simpan_pokok" value="<?php echo $row['simpan_pokok']; ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../public/footer.php"; ?>

</html>
