<!-- ... (bagian head HTML lainnya) ... -->

<body>
    <?php include 'header.php' ?>
    <?php include 'slidebar_anggota.php' ?>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h3>Form Pembayaran Angsuran</h3>
                        <br>
                        <?php
                        include "../control/koneksi.php";

                        // Check if the form is submitted
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Retrieve data from angsuran_berlangsung table
                            $id_transaksi_param = isset($_POST['id_transaksi']) ? $_POST['id_transaksi'] : null;

                            if ($id_transaksi_param) {
                                // ... (bagian validasi dan pengolahan data lainnya) ...
                            } else {
                                echo '<p style="color: red;">ID transaksi tidak valid.</p>';
                            }
                        }
                        ?>

                        <form class="forms-sample" method="post" action="pembayaran_angsuran_test.php?id_transaksi=<?php echo isset($_GET['id_transaksi']) ? $_GET['id_transaksi'] : ''; ?>" enctype="multipart/form-data">
                            <!-- Tambahkan input tersembunyi untuk menyimpan id_transaksi -->
                            <!-- Saya asumsikan bahwa Anda mendapatkan id_transaksi dari data pinjaman yang ditampilkan sebelumnya -->
                            <?php
                            if (isset($_GET['id_transaksi'])) {
                                $id_transaksi_param = $_GET['id_transaksi'];
                                echo "<input type='hidden' name='id_transaksi' value='" . $id_transaksi_param . "'>";
                            }
                            ?>

                            <div class="form-group">
                                <label for="jumlah_bayar">Jumlah Pembayaran (Minimal <?php echo isset($min_payment) ? $min_payment : ''; ?>):</label>
                                <input class="form-control" type="number" name="jumlah_bayar" min="<?php echo isset($min_payment) ? $min_payment : 1; ?>" max="<?php echo $jumlah_angsuran; ?>" step="1" required>
                            </div>
                            <div class="form-group">
                                <label for="bukti_transfer">Bukti Transfer (Gambar):</label>
                                <input class="form-control file-upload-info" type="file" name="bukti_transfer" accept="image/*" required>
                            </div>
                            <button class="btn btn-gradient-primary me-2" type="submit">Bayar Angsuran</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "../public/footer.php" ?>
</body>

</html>
