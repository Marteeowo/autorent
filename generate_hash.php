<?php
// See fail valmistab testparoolile uue räsi.

$password_to_hash = 'Passw0rd'; // Muuda siin parooli, millele soovid räsi luua.
$hashed_password = password_hash($password_to_hash, PASSWORD_BCRYPT);

echo "Original Password: " . htmlspecialchars($password_to_hash) . "<br>";
echo "Hashed Password: " . htmlspecialchars($hashed_password) . "<br>";
echo "Length of Hash: " . strlen($hashed_password) . "<br>";

if (password_verify($password_to_hash, $hashed_password)) {
    echo "Verification successful!";
} else {
    echo "Verification failed!";
}
?>