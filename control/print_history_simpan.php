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

        $sql = "SELECT * FROM transaksi_simpan WHERE MONTH(tanggal_simpan) = $selectedMonth";
        $result = mysqli_query($conn, $sql);

        $request_simpan_data = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $request_simpan_data[] = $row;
            }
        }

        mysqli_close($conn);

        // Check if there is data to download
        if (!empty($request_simpan_data)) {
            // Set the content type and headers for CSV
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="riwayat_simpan.csv"');

            // Create a file pointer connected to the output stream
            $output = fopen('php://output', 'w');

            // Output the CSV header
            fputcsv($output, array('ID Transaksi', 'ID Anggota', 'Jumlah Transfer', 'Bukti Transfer', 'Status', 'Jumlah Diterima', 'Tanggal Simpan'));

            // Output data rows
            foreach ($request_simpan_data as $row) {
                fputcsv($output, $row);
            }

            // Close the file pointer
            fclose($output);

            // Stop the script after sending the file
            exit();
        } else {
            echo "Tidak ada data transaksi simpanan untuk diunduh.";
        }
    }
}
?>
