<?php
/**
 * Passwort-Migrations-Skript
 * Konvertiert alte SHA256-Hashes zu password_hash (BCrypt/Argon2)
 * 
 * ACHTUNG: Dieses Skript setzt alle Passwörter auf ein temporäres Standardpasswort!
 * Benutzer müssen nach dem ersten Login ein neues Passwort setzen.
 */

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/app/core/Database.php';

echo "=== Passwort-Migration ===\n\n";
echo "WARNUNG: Dieses Skript setzt alle Passwörter auf 'TempPass2024!'\n";
echo "Benutzer müssen nach dem Login ein neues Passwort setzen.\n\n";
echo "Fortfahren? (y/n): ";

$handle = fopen("php://stdin", "r");
$line = fgets($handle);
if (trim($line) != 'y') {
    echo "Abgebrochen.\n";
    exit;
}

try {
    $db = new Database();
    
    // Alle User holen
    $sql = "SELECT id, email, vorname, nachname FROM users";
    $stmt = $db->query($sql);
    $users = $db->fetchAll($stmt);
    
    echo "\nGefundene Benutzer: " . count($users) . "\n\n";
    
    $tempPassword = 'TempPass2024!';
    $newHash = password_hash($tempPassword, PASSWORD_DEFAULT);
    
    $updateCount = 0;
    
    foreach ($users as $user) {
        // Passwort-Hash updaten
        $updateSql = "UPDATE users SET pw_hash = ? WHERE id = ?";
        $result = $db->query($updateSql, [$newHash, $user['id']]);
        
        if ($result) {
            $updateCount++;
            echo "✓ User #{$user['id']} - {$user['vorname']} {$user['nachname']} ({$user['email']}) aktualisiert\n";
        } else {
            echo "✗ Fehler bei User #{$user['id']}\n";
        }
    }
    
    echo "\n=== Migration abgeschlossen ===\n";
    echo "Aktualisierte Benutzer: $updateCount von " . count($users) . "\n";
    echo "\nTemporäres Passwort für alle: $tempPassword\n";
    echo "\n⚠️ WICHTIG: Benutzer müssen nach dem Login ihr Passwort ändern!\n";
    
} catch (Exception $e) {
    echo "\n✗ Fehler: " . $e->getMessage() . "\n";
    exit(1);
}

