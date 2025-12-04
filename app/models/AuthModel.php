<?php
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Database.php';

class AuthModel extends Model {

    public static function verifyUser($email, $password) {
        // SHA256 BINARY (kompatibel zu HASHBYTES)
        $pw_hash = hash('sha256', mb_convert_encoding($password, 'UTF-16LE'), true);
        file_put_contents('debug_pw.txt', "email: $email\npassword: $password\nhash: ".bin2hex($pw_hash)."\n", FILE_APPEND);

        // Database-Objekt verwenden
        $db = new Database();
        $conn = $db->getConnection();

        if (!$conn) {
            die("DB-Verbindung fehlgeschlagen: " . print_r($db->getLastError(), true));
        }

        $sql = "SELECT id, rolle_id, vorname, nachname 
                FROM users 
                WHERE email = ? AND pw_hash = ?";

        $params = [ $email, $pw_hash ];

        $stmt = $db->query($sql, $params);

        if (!$stmt) {
            file_put_contents('debug_pw.txt', "Kein Statement!\n", FILE_APPEND);
            return false;
        }

        $rows = $db->fetchAll($stmt);
        file_put_contents('debug_pw.txt', "rows: ".print_r($rows, true)."\n", FILE_APPEND);

        if (count($rows) === 1) {
            return $rows[0];
        }

        return false;
    }
}
