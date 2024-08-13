<?php
include 'connection.php';

if (isset($_GET['date'])) {
    $date = $_GET['date'];
    $bookedTimes = [];

    $query = mysqli_query($conn, "SELECT jam_kunjugan FROM pendaftaran WHERE tgl_pendaftaran = '$date'");
    while ($row = mysqli_fetch_array($query)) {
        $bookedTimes[] = $row['jam_kunjugan'];
    }

    echo json_encode($bookedTimes);
    exit;
}
?>
