<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * AUTH-MODEL - Benutzer-Authentifizierung
 * ═══════════════════════════════════════════════════════════════
 * 
 * Dieses Model ist zuständig für:
 * - Verifizierung von Login-Daten (E-Mail + Passwort)
 * - Passwort-Prüfung mit password_verify()
 * - Sicherer Vergleich von Passwort-Hashes
 * 
 * WICHTIG FÜR IHK-PRÜFUNG:
 * - Passwörter werden NIEMALS im Klartext gespeichert!
 * - Stattdessen: Hash-Verfahren (Argon2ID / BCrypt)
 * - password_verify() vergleicht eingegebenes PW mit Hash
 * 
 * ═══════════════════════════════════════════════════════════════
 */

// Benötigte Klassen laden
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Database.php';

class AuthModel extends Model {
    
    /**
     * BENUTZER VERIFIZIEREN (Login-Prüfung)
     * 
     * Diese Methode wird vom AuthController aufgerufen.
     * Sie prüft ob E-Mail + Passwort korrekt sind.
     * 
     * ABLAUF:
     * 1. User aus Datenbank laden (anhand E-Mail)
     * 2. Existiert User?
     * 3. Ist User aktiv?
     * 4. Passwort korrekt? (Hash-Vergleich!)
     * 5. Rückgabe: User-Array oder false
     * 
     * @param string $email Die E-Mail-Adresse
     * @param string $password Das Passwort (Klartext!)
     * @return array|false User-Array bei Erfolg, false bei Fehler
     */
    public static function verifyUser($email, $password) {
        // ═══ SCHRITT 1: Datenbankverbindung herstellen ═══
        $db = new Database();
        $conn = $db->getConnection();
        
        // Verbindung erfolgreich?
        if (!$conn) {
            // NEIN → Fehler loggen und abbrechen
            error_log("DB-Verbindung fehlgeschlagen: " . print_r($db->getLastError(), true));
            return false;
        }
        
        // ═══ SCHRITT 2: User aus Datenbank laden ═══
        // SQL-Query vorbereiten (? = Platzhalter für Parameter)
        // pw_hash wird benötigt um Passwort zu vergleichen!
        $sql = "SELECT id, rolle_id, vorname, nachname, aktiv, pw_hash FROM users WHERE email = ?";
        
        // Query ausführen (? wird durch $email ersetzt)
        $stmt = $db->query($sql, [$email]);
        
        // Query erfolgreich?
        if (!$stmt) {
            return false;  // Fehler bei Query
        }
        
        // Ergebnisse holen (als Array)
        $rows = $db->fetchAll($stmt);
        
        // ═══ SCHRITT 3: Genau 1 User gefunden? ═══
        if (count($rows) !== 1) {
            // NEIN → User existiert nicht (oder mehrfach, was nicht sein sollte)
            
            // ═══ WICHTIG: TIMING-ATTACK-SCHUTZ! ═══
            // Auch wenn User nicht existiert, führen wir password_verify() aus.
            // WARUM? Damit ein Angreifer nicht an der Antwortzeit erkennen kann,
            // ob ein User existiert oder nicht!
            password_verify($password, '$2y$10$invalidhashtopreventtimingattack');
            return false;
        }
        
        // User-Daten holen (erstes und einziges Element)
        $user = $rows[0];
        
        // ═══ SCHRITT 4: Ist User aktiv? ═══
        // Inaktive User dürfen sich nicht einloggen
        if (!$user['aktiv']) {
            return false;  // User ist deaktiviert
        }
        
        // ═══ SCHRITT 5: PASSWORT-VERGLEICH ═══
        // Passwort-Hash aus Datenbank holen
        // pw_hash ist als VARBINARY gespeichert, PHP bekommt es als String
        $storedHash = $user['pw_hash'];
        
        // ═══ MIGRATION: Alter SHA256-Hash? ═══
        // Früher wurde SHA256 verwendet (UNSICHER!)
        // Jetzt: password_hash() mit Argon2ID oder BCrypt (SICHER!)
        // Dieser Code unterstützt beide Varianten (für Migration)
        if (is_string($storedHash) && strlen($storedHash) === 32) {
            // ═══ LEGACY: SHA256-Hash (32 Bytes) ═══
            // Dies ist der ALTE Weg (nicht mehr empfohlen!)
            // Eingabe-Passwort in SHA256 umwandeln (UTF-16LE für SQL Server)
            $legacyHash = hash('sha256', mb_convert_encoding($password, 'UTF-16LE'), true);
            
            // Hashes vergleichen mit hash_equals() (Timing-Safe!)
            if (hash_equals($storedHash, $legacyHash)) {
                // ✅ Passwort stimmt!
                // HINWEIS: Hier könnte man automatisch auf password_hash upgraden
                // Aber für jetzt akzeptieren wir es einfach
                
                // pw_hash aus Array entfernen (Sicherheit!)
                unset($user['pw_hash']);
                return $user;
            }
        } else {
            // ═══ MODERN: password_hash() / password_verify() ═══
            // Das ist der SICHERE Weg! (Argon2ID oder BCrypt)
            
            // password_verify() macht folgendes:
            // 1. Hash-Algorithmus erkennen (aus $storedHash)
            // 2. Passwort mit gleichem Algorithmus hashen
            // 3. Hashes vergleichen (Timing-Safe!)
            // 4. Rückgabe: true bei Übereinstimmung, false bei Fehler
            if (password_verify($password, $storedHash)) {
                // ✅ Passwort stimmt!
                
                // pw_hash aus Array entfernen (Sicherheit!)
                // Wir wollen den Hash NIEMALS an den Browser senden!
                unset($user['pw_hash']);
                return $user;
            }
        }
        
        // ❌ Passwort stimmt nicht
        return false;
    }
}

// ═══════════════════════════════════════════════════════════════
// ENDE DES AUTH-MODELS
// 
// WICHTIGE KONZEPTE FÜR IHK-PRÜFUNG:
// 
// 1. PASSWORT-HASHING:
//    - Klartext-Passwort → Hash-Funktion → Hash (gespeichert in DB)
//    - Hash kann NICHT rückgängig gemacht werden (Einwegfunktion!)
//    - Beim Login: Eingabe hashen + mit gespeichertem Hash vergleichen
// 
// 2. HASH-ALGORITHMEN:
//    - SHA256: VERALTET! Zu schnell (Brute-Force möglich)
//    - BCrypt: GUT! Langsam = sicher gegen Brute-Force
//    - Argon2ID: BESTE WAHL! Noch sicherer als BCrypt
// 
// 3. password_verify():
//    - PHP-Funktion für sicheren Passwort-Vergleich
//    - Timing-Safe (verhindert Timing-Attacks)
//    - Funktioniert mit BCrypt und Argon2ID
// 
// 4. TIMING-ATTACK:
//    - Angreifer misst Antwortzeit
//    - Kann dadurch Rückschlüsse ziehen (User existiert? Passwort stimmt teilweise?)
//    - Schutz: Immer gleich lange Operationen ausführen
// 
// ═══════════════════════════════════════════════════════════════
