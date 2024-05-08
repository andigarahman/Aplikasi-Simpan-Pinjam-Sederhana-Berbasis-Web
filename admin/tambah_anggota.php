<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tambah Anggota</title>
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
<?php include 'slidebar_admin.php' ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Form Tambah Anggota</h4>
                        <p class="card-description"> Masukan Data </p>
                        <form class="forms-sample" action="../control/tambah_anggota.php" method="post">
                            <div class="form-group">
                                <label for="id_anggota">Kode Anggota</label>
                                <input type="text" class="form-control" id="id_anggota" name="id_anggota" placeholder="Kode Anggota" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_anggota">Nama Anggota</label>
                                <input type="text" class="form-control" id="nama_anggota" name="nama_anggota" placeholder="Nama Anggota" required>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="pria">Pria</option>
                                    <option value="wanita">Wanita</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" required>
                            </div>
                            <div class="form-group">
                                <label for="no_tlp">No Tlp</label>
                                <input type="text" class="form-control" id="no_tlp" name="no_tlp" placeholder="No Tlp" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <label for="simpanan">Biaya Pendaftaran</label>
                                <input type="number" class="form-control" id="simpanan" name="simpanan" placeholder="Biaya Pendaftaran" required>
                            </div>
                            <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                            <button type="reset" class="btn btn-light">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "../public/footer.php" ?>
</html>