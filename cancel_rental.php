<?php
session_start();
include('config.php');

// Seda toimingut saavad teha ainult sisselogitud kliendid.
if (!isset($_SESSION['roll']) || $_SESSION['roll'] !== 'client' || !isset($_GET['rental_id']) || !isset($_GET['car_id'])) {
    header("Location: my_rentals.php");
    exit();
}

$username = $_SESSION['tuvastamine'];
$rental_id = intval($_GET['rental_id']);
$car_id = intval($_GET['car_id']);

// Leiame kõigepealt kliendi ID.
$stmt = mysqli_prepare($yhendus, "SELECT id FROM clients WHERE username = ?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $client_row = mysqli_fetch_assoc($res);
    $client_id = $client_row['id'] ?? null;
    mysqli_stmt_close($stmt);
}

if (!$client_id) {
    header("Location: my_rentals.php?error=client_not_found");
    exit();
}

// Kontrollime, et rent kuuluks sellele kliendile ja oleks aktiivne.
$stmt = mysqli_prepare($yhendus, "SELECT id FROM rentals WHERE id = ? AND client_id = ? AND end_date >= CURRENT_DATE AND status = 'active'");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ii", $rental_id, $client_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($res) === 0) {
        header("Location: my_rentals.php?error=rental_not_found_or_not_active_for_cancellation");
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: my_rentals.php?error=db_check_rental_failed");
    exit();
}

// Tühistame rendi ning märgime auto uuesti vabaks.
$yhendus->begin_transaction(); // Alustame tehingut, et muudatused püsiksid koos.
try {
    $cancel_rental_stmt = mysqli_prepare($yhendus, "UPDATE rentals SET status = 'cancelled' WHERE id = ? AND client_id = ?");
    mysqli_stmt_bind_param($cancel_rental_stmt, "ii", $rental_id, $client_id);
    mysqli_stmt_execute($cancel_rental_stmt);

    $update_car_stmt = mysqli_prepare($yhendus, "UPDATE cars SET status = 'vaba' WHERE id = ?");
    mysqli_stmt_bind_param($update_car_stmt, "i", $car_id);
    mysqli_stmt_execute($update_car_stmt);

    $yhendus->commit(); // Kinnitame tehingu pärast edukat lõpetamist.
    header("Location: my_rentals.php?success=rental_cancelled");
    exit();
} catch (mysqli_sql_exception $exception) {
    $yhendus->rollback(); // Vea korral võtame muudatused tagasi.
    header("Location: my_rentals.php?error=cancel_failed");
    exit();
}
?>