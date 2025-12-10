<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * USER-MODEL - Verwaltung von Benutzern
 * ═══════════════════════════════════════════════════════════════
 * 
 * Dieses Model verwaltet die "users"-Tabelle in der Datenbank.
 * Es stellt Methoden bereit zum:
 * - Abrufen aller User (mit Rollennamen)
 * - Abrufen eines einzelnen Users
 * - Erstellen neuer User (mit Passwort-Hashing)
 * - Aktualisieren bestehender User
 * - Aktivieren/Deaktivieren von Usern
 * 
 * WICHTIG: User ≠ Mitarbeiter!
 * - User = Personen mit Login-Zugang zum System
 * - Mitarbeiter = Personen, die Hardware/Software bekommen
 * 
 * ═══════════════════════════════════════════════════════════════
 */

// Benötigte Klassen laden
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Database.php';

class UserModel extends Model {
    
    /**
     * ALLE USER ABRUFEN
     * 
     * Lädt alle Benutzer aus der Datenbank, sortiert nach Nachname.
     * Wird verwendet für die User-Übersichtsseite.
     * 
     * JOIN mit rollen-Tabelle:
     * - Damit wir den Rollennamen anzeigen können (z.B. "Administrator")
     * - LEFT JOIN = Auch User ohne Rolle werden angezeigt (sollte nicht vorkommen)
     * 
     * @return array Array mit allen Usern (inkl. rolle_name)
     */
    public static function getAll() {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL-Query mit LEFT JOIN und Sortierung
        // u.* = alle Spalten aus users-Tabelle
        // r.name as rolle_name = Rollenname aus rollen-Tabelle
        $sql = "SELECT u.*, r.name as rolle_name FROM users u LEFT JOIN rollen r ON u.rolle_id = r.id ORDER BY u.nachname, u.vorname";
        
        // Query ausführen (keine Parameter nötig)
        $stmt = $db->query($sql);
        
        // Alle Zeilen holen (oder leeres Array bei Fehler)
        return $stmt ? $db->fetchAll($stmt) : [];
    }
    
    /**
     * EINZELNEN USER ABRUFEN (anhand ID)
     * 
     * Lädt einen spezifischen User aus der Datenbank.
     * Wird verwendet für:
     * - Bearbeiten-Formular (Daten vorausfüllen)
     * - Detail-Ansicht
     * 
     * @param int $id Die User-ID
     * @return array|null User-Array oder null wenn nicht gefunden
     */
    public static function getById($id) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL-Query mit WHERE-Bedingung
        // ? wird durch $id ersetzt (Prepared Statement = SQL-Injection-Schutz!)
        $sql = "SELECT * FROM users WHERE id = ?";
        
        // Query ausführen mit Parameter $id
        $stmt = $db->query($sql, [$id]);
        
        // Ergebnisse holen
        $rows = $stmt ? $db->fetchAll($stmt) : [];
        
        // Ersten (und einzigen) Eintrag zurückgeben, oder null
        // ?? = Null Coalescing Operator
        return $rows[0] ?? null;
    }
    
    /**
     * NEUEN USER ERSTELLEN
     * 
     * Legt einen neuen Benutzer in der Datenbank an.
     * Das Passwort wird automatisch gehasht (sicher gespeichert)!
     * 
     * WICHTIG:
     * - Passwort wird NIEMALS im Klartext gespeichert!
     * - password_hash() erzeugt einen sicheren Hash (Argon2ID oder BCrypt)
     * - Hash kann nicht rückgängig gemacht werden (Einwegfunktion)
     * 
     * @param array $data Assoziatives Array mit User-Daten:
     *                    - vorname (string)
     *                    - nachname (string)
     *                    - email (string)
     *                    - rolle_id (int)
     *                    - aktiv (bool/int: 1=aktiv, 0=inaktiv)
     *                    - password (string, Klartext!)
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function create($data) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL INSERT Statement vorbereiten
        // 6 Platzhalter (?) für 6 Werte
        $sql = "INSERT INTO users (vorname, nachname, email, rolle_id, aktiv, pw_hash) VALUES (?, ?, ?, ?, ?, ?)";
        
        // ═══ PASSWORT-HASHING ═══
        // Das Passwort wird mit password_hash() gehasht
        $pw_hash = null;
        if (!empty($data['password'])) {
            // PASSWORD_DEFAULT = aktuell bester Algorithmus
            // Aktuell: Argon2ID (falls verfügbar), sonst BCrypt
            // Der Hash sieht z.B. so aus: $argon2id$v=19$m=65536,t=4,p=1$...
            $pw_hash = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // SQL ausführen mit Parametern (in der richtigen Reihenfolge!)
        return $db->query($sql, [ 
            $data['vorname'], 
            $data['nachname'], 
            $data['email'], 
            $data['rolle_id'], 
            $data['aktiv'], 
            $pw_hash 
        ]);
    }
    
    /**
     * USER AKTUALISIEREN
     * 
     * Aktualisiert einen bestehenden User in der Datenbank.
     * 
     * BESONDERHEIT:
     * - Passwort wird nur geändert wenn $data['password'] gesetzt ist
     * - Wenn leer → Passwort bleibt unverändert
     * - Wenn gesetzt → Neuer Hash wird erstellt
     * 
     * @param int $id Die User-ID
     * @param array $data Assoziatives Array mit User-Daten (siehe create())
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function update($id, $data) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // Variablen für dynamisches SQL
        $pw_set = '';  // Wird evtl. ", pw_hash = ?" bei Passwort-Änderung
        $params = [ $data['vorname'], $data['nachname'], $data['email'], $data['rolle_id'], $data['aktiv'] ];
        
        // ═══ PASSWORT ÄNDERN? ═══
        // Nur wenn Passwort-Feld ausgefüllt wurde
        if (!empty($data['password'])) {
            // JA → pw_hash in UPDATE einbauen
            $pw_set = ", pw_hash = ?";
            
            // Passwort hashen und zu Parametern hinzufügen
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        // NEIN → Passwort bleibt unverändert
        
        // User-ID als letzten Parameter hinzufügen (für WHERE id = ?)
        $params[] = $id;
        
        // SQL zusammenbauen (mit oder ohne pw_hash-Update)
        // {$pw_set} wird durch "" oder ", pw_hash = ?" ersetzt
        $sql = "UPDATE users SET vorname=?, nachname=?, email=?, rolle_id=?, aktiv=?{$pw_set} WHERE id = ?";
        
        // SQL ausführen
        return $db->query($sql, $params);
    }
    
    /**
     * USER AKTIVIEREN/DEAKTIVIEREN
     * 
     * Setzt den aktiv-Status eines Users.
     * Inaktive User können sich nicht einloggen!
     * 
     * VERWENDUNG:
     * - "Löschen" von Usern (besser: deaktivieren statt löschen!)
     * - Temporäres Sperren von Accounts
     * - Reaktivieren von Usern
     * 
     * @param int $id Die User-ID
     * @param bool $aktiv True = aktivieren, False = deaktivieren
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function setActive($id, $aktiv) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL UPDATE Statement
        // Nur aktiv-Feld wird geändert
        $sql = "UPDATE users SET aktiv=? WHERE id=?";
        
        // SQL ausführen
        // $aktiv wird zu 1 oder 0 konvertiert
        return $db->query($sql, [$aktiv, $id]);
    }
}

// ═══════════════════════════════════════════════════════════════
// ENDE DES USER-MODELS
// 
// WICHTIGE KONZEPTE FÜR IHK-PRÜFUNG:
// 
// 1. CRUD-OPERATIONEN:
//    - Create (Erstellen) → create()
//    - Read (Lesen) → getAll(), getById()
//    - Update (Aktualisieren) → update()
//    - Delete (Löschen) → setActive() statt echtem Löschen!
// 
// 2. SOFT DELETE vs. HARD DELETE:
//    - Hard Delete = Zeile aus Datenbank löschen (unwiederbringlich!)
//    - Soft Delete = Zeile nur als "inaktiv" markieren (kann reaktiviert werden)
//    - Wir verwenden Soft Delete (aktiv-Spalte)
// 
// 3. PREPARED STATEMENTS:
//    - Alle SQL-Queries verwenden Platzhalter (?)
//    - Schützt vor SQL-Injection-Angriffen
//    - Database-Klasse ersetzt ? durch escapedte Werte
// 
// 4. PASSWORT-SICHERHEIT:
//    - NIEMALS Klartext-Passwörter speichern!
//    - password_hash() für Hashing
//    - password_verify() für Vergleich (in AuthModel)
//    - PASSWORD_DEFAULT = aktuell bester Algorithmus
// 
// ═══════════════════════════════════════════════════════════════
