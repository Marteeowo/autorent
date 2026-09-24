<?php
session_start();
include('../config.php');

if (!isset($_SESSION['roll']) || $_SESSION['roll'] !== 'admin' || empty($_GET['rental_id'])) {
    header("Location: admin.php");
    exit();
}

$rental_id = intval($_GET['rental_id']);

// Toome broneeringu andmed ja kontrollime selle aktiivsust.
$stmt = mysqli_prepare($yhendus, "SELECT car_id, start_date, end_date FROM rentals WHERE id = ? AND status = 'active'");
mysqli_stmt_bind_param($stmt, "i", $rental_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$rental = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if ($rental) {
    $car_id = $rental['car_id'];
    $today = date('Y-m-d');
    
    // Hoiame seotud muudatused ühe tehinguna.
    mysqli_begin_transaction($yhendus);
    
    try {
        // Märgime broneeringu tühistatuks.
        $upd_stmt = mysqli_prepare($yhendus, "UPDATE rentals SET status = 'cancelled' WHERE id = ?");
        mysqli_stmt_bind_param($upd_stmt, "i", $rental_id);
        mysqli_stmt_execute($upd_stmt);
        mysqli_stmt_close($upd_stmt);

        // Tänase või varasema algusaja korral teeme ka auto vabaks.
        if ($rental['start_date'] <= $today && $rental['end_date'] >= $today) {
            $car_upd = mysqli_prepare($yhendus, "UPDATE cars SET status = 'vaba' WHERE id = ?");
            mysqli_stmt_bind_param($car_upd, "i", $car_id);
            mysqli_stmt_execute($car_upd);
            mysqli_stmt_close($car_upd);
        }

        mysqli_commit($yhendus);
        header("Location: admin.php?msg=tyhistatud");
    } catch (Exception $e) {
        mysqli_rollback($yhendus);
        header("Location: admin.php?msg=error");
    }
} else {
    header("Location: admin.php");
}
exit();
?>