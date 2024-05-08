<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['download_data'])) {
    if (isset($_POST['selected_month'])) {
        // Fetch data from the database
        $user = "root";
        $pw = "";
        $db = "koperasi_sp";
        $host = "localhost";

        $conn = mysqli_connect($host, $user, $pw, $db);

        if (!$conn) {
            die("Koneksi database gagal " . mysqli_connect_error());
        }

        $selectedMonth = $_POST['selected_month'];

        $sql = "SELECT * FROM angsuran_berlangsung WHERE MONTH(tanggal_disetujui) = $selectedMonth";
        $result = mysqli_query($conn, $sql);

        $history_angsuran_data = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $history_angsuran_data[] = $row;
            }
        }

        mysqli_close($conn);

        // Check if there is data to download
        if (!empty($history_angsuran_data)) {
            // Set the content type and headers for CSV
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="riwayat_angsuran.csv"');

            // Create a file pointer connected to the output stream
            $output = fopen('php://output', 'w');

            // Output the CSV header
            fputcsv($output, array('ID Angsuran', 'ID Transaksi', 'ID Anggota', 'Jumlah Angsuran', 'Tanggal Disetujui', 'Bukti Transfer', 'Status', 'Lama Angsuran', 'Tenggat Waktu'));

            // Output data rows
            foreach ($history_angsuran_data as $row) {
                fputcsv($output, $row);
            }

            // Close the file pointer
            fclose($output);

            // Stop the script after sending the file
            exit();
        } else {
            echo "Tidak ada data angsuran berlangsung untuk diunduh.";
        }
    }
}
?>
