<!DOCTYPE html>
<html lang="en">
<head>
    <title>Syarat.PDF</title>
</head>

<body style="margin: 0; height: 100vh; display: flex; align-items: center; justify-content: center; background-color: #fff;">

    <div style="text-align: center; width: 100%; height: 100%;">

        <h2>Syarat Peminjaman</h2>

        <?php
        // Database connection
        $koneksi = include '../control/koneksi.php';

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Get ID from the query string
        $id_transaksi = $_GET['id'];

        // Fetch syarat_peminjaman data
        $querySelect = "SELECT syarat_peminjaman FROM permintaan_peminjaman WHERE id_transaksi = ?";
        $stmt = mysqli_prepare($conn, $querySelect);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $id_transaksi);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $syarat_peminjaman);
            mysqli_stmt_fetch($stmt);

            echo "<iframe src='../anggota/" . $syarat_peminjaman . "' width='100%' height='100%' style='border: none;'></iframe>";

            mysqli_stmt_close($stmt);
        } else {
            echo "Error fetching data: " . mysqli_error($conn);
        }

        mysqli_close($conn);
        ?>

    </div>

</body>
</html>
