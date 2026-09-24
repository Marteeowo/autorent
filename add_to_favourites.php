<?php
session_start();
include('config.php');

// Ainult sisseloginud kliendid saavad seda funktsiooni kasutada.
if (!isset($_SESSION['roll']) || $_SESSION['roll'] !== 'client' || !isset($_GET['car_id'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['tuvastamine'];
$car_id = intval($_GET['car_id']);

// Leiame kliendi ID kasutajanime järgi, ainult veidi teise sõnastusega.
$stmt = mysqli_prepare($yhendus, "SELECT id FROM clients WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$client = mysqli_fetch_assoc($result);
$client_id = $client['id'];
mysqli_stmt_close($stmt);

// Vaatame üle, kas auto on juba lemmikute nimekirjas.
$stmt = mysqli_prepare($yhendus, "SELECT id FROM favourites WHERE client_id = ? AND car_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $client_id, $car_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    // Kui see on juba olemas, võtame selle nimekirjast ära.
    $stmt_toggle = mysqli_prepare($yhendus, "DELETE FROM favourites WHERE client_id = ? AND car_id = ?");
} else {
    // Kui seda veel pole, lisame auto nimekirja.
    $stmt_toggle = mysqli_prepare($yhendus, "INSERT INTO favourites (client_id, car_id) VALUES (?, ?)");
}

mysqli_stmt_bind_param($stmt_toggle, "ii", $client_id, $car_id);
mysqli_stmt_execute($stmt_toggle);

$redirect = 'index.php';
if (isset($_GET['redirect']) && $_GET['redirect'] === 'favourites') {
    $redirect = 'favourites.php';
}

header("Location: " . $redirect);
exit();
?>