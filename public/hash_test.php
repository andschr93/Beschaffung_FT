<?php
// Zum Testen im Browser: http://localhost/Beschaffung_FT/public/hash_test.php?pw=test1234

$pass = $_GET['pw'] ?? 'test1234';
echo "<strong>Passwort:</strong> $pass<br>";
$hash = hash('sha256', mb_convert_encoding($pass, 'UTF-16LE'), true); // binär wie HASHBYTES
$hash_hex = bin2hex($hash);
echo "<strong>SHA256-Hash (Hex, UTF-16LE):</strong> $hash_hex";
?>