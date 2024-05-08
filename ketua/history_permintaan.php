<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Riwayat Permintaan Peminjaman</title>
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
<?php include 'slidebar_ketua.php'; ?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Riwayat Permintaan Peminjaman</h4>

                    <!-- Form Pencarian -->
                    <form class="forms-sample" method="get" action="">
                        <div class="form-group">
                            <label for="selected_month">Pilih Bulan:</label>
                            <select name="selected_month" id="selected_month">
                                <?php
                                // Display options for each month
                                for ($i = 1; $i <= 12; $i++) {
                                    $month = date('F', mktime(0, 0, 0, $i, 1));
                                    $selected = (isset($_GET['selected_month']) && $_GET['selected_month'] == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$month</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="selected_year">Pilih Tahun:</label>
                            <select name="selected_year" id="selected_year">
                                <?php
                                // Display options for years (adjust the range as needed)
                                $currentYear = date('Y');
                                for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
                                    $selected = (isset($_GET['selected_year']) && $_GET['selected_year'] == $year) ? 'selected' : '';
                                    echo "<option value='$year' $selected>$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="selected_status">Pilih Status:</label>
                            <select name="selected_status" id="selected_status">
                                <option value="" <?php echo (!isset($_GET['selected_status']) || $_GET['selected_status'] === '') ? 'selected' : ''; ?>>Semua</option>
                                <option value="menunggu" <?php echo (isset($_GET['selected_status']) && $_GET['selected_status'] === 'menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                                <option value="disetujui" <?php echo (isset($_GET['selected_status']) && $_GET['selected_status'] === 'diterima') ? 'selected' : ''; ?>>Disetujui</option>
                                <option value="ditolak" <?php echo (isset($_GET['selected_status']) && $_GET['selected_status'] === 'ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </form>

                    <br>
                    <hr>

                    <!-- Table -->
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>ID Anggota</th>
                                <th>Jumlah Peminjaman</th>
                                <th>Lama Angsuran</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Check if the form is submitted
                        if (isset($_GET['selected_year']) || isset($_GET['selected_month'])) {
                            // Fetch data from the database
                            $user = "root";
                            $pw = "";
                            $db = "koperasi_sp";
                            $host = "localhost";

                            $conn = mysqli_connect($host, $user, $pw, $db);

                            if (!$conn) {
                                die("Koneksi database gagal " . mysqli_connect_error());
                            }

                            // Fetch data from the database
                            $selectedMonth = $_GET['selected_month'];
                            $selectedYear = $_GET['selected_year'];
                            $selectedStatus = isset($_GET['selected_status']) ? $_GET['selected_status'] : '';

                            $sql = "SELECT * FROM permintaan_peminjaman WHERE MONTH(tanggal_pengajuan) = $selectedMonth AND YEAR(tanggal_pengajuan) = $selectedYear";

                            // Add condition for status, if selected
                            if ($selectedStatus !== '') {
                                $sql .= " AND status = '$selectedStatus'";
                            }

                            $result = mysqli_query($conn, $sql);

                            // Check for query execution success
                            if (!$result) {
                                die("Query execution failed: " . mysqli_error($conn));
                            }

                            $history_peminjaman_data = array();

                            // Check for the number of rows
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Display your data here
                                    echo "<tr>";
                                    echo "<td>" . $row['id_transaksi'] . "</td>";
                                    echo "<td>" . $row['id_anggota'] . "</td>";
                                    echo "<td>" . $row['jumlah_peminjaman'] . "</td>";
                                    echo "<td>" . $row['lama_angsuran'] . "</td>";
                                    echo "<td>" . $row['tanggal_pengajuan'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>Tidak ada data permintaan peminjaman.</td></tr>";
                            }

                            mysqli_close($conn);
                        }
                        ?>
                        </tbody>
                    </table>
                    <br>
                    <hr>
                    <form class="forms-sample" method="post" action="../control/print_history_pAngsuran.php">
                        <input type="hidden" name="selected_year" value="<?php echo isset($_GET['selected_year']) ? $_GET['selected_year'] : ''; ?>">
                        <input type="hidden" name="selected_month" value="<?php echo isset($_GET['selected_month']) ? $_GET['selected_month'] : ''; ?>">
                        <button type="submit" class="btn btn-success" name="download_data">Download Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../public/footer.php"; ?>

</html>
