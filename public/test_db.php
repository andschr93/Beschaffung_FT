<?php
require_once __DIR__ . '/../app/core/Database.php';

try {
    $db = new Database();
    echo "<h2 style='color:green'>✔ Verbindung erfolgreich!</h2>";
} catch (Exception $e) {
    echo "<h2 style='color:red'>✘ Fehler:</h2>";
    echo "<pre>{$e->getMessage()}</pre>";
}
