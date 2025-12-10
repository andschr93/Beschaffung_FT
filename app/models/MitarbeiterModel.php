<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * MITARBEITER-MODEL - Verwaltung von Mitarbeitern
 * ═══════════════════════════════════════════════════════════════
 * 
 * Dieses Model verwaltet die "mitarbeiter"-Tabelle.
 * Hier werden alle neuen Mitarbeiter gespeichert, für die
 * Hardware und Software beschafft werden muss.
 * 
 * WICHTIG: Mitarbeiter ≠ User!
 * - Mitarbeiter = Personen, die Hardware/Software bekommen (DIESE Tabelle)
 * - User = Personen mit Login-Zugang zum System (users-Tabelle)
 * 
 * WICHTIGE SPALTEN:
 * - vorname, nachname, email: Persönliche Daten
 * - bereich_id, abteilung_id: Organisatorische Zuordnung
 * - startdatum: Wann fängt der Mitarbeiter an?
 * - status: Onboarding-Status (z.B. "Offen", "In Bearbeitung", "Abgeschlossen")
 * - prioritaet: Dringlichkeit (z.B. "Normal", "Hoch")
 * 
 * ═══════════════════════════════════════════════════════════════
 */

// Benötigte Klassen laden
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Database.php';

class MitarbeiterModel extends Model {
    
    /**
     * ALLE MITARBEITER ABRUFEN
     * 
     * Lädt alle Mitarbeiter aus der Datenbank.
     * Wird verwendet für die Mitarbeiter-Übersichtsseite.
     * 
     * BESONDERHEIT:
     * - 2x LEFT JOIN → Zeigt auch Bereichs- und Abteilungsnamen an
     * - Nicht nur bereich_id (Zahl), sondern auch bereich_name (Text)
     * 
     * @return array Array mit allen Mitarbeitern (inkl. Bereich/Abteilung)
     */
    public static function getAll() {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL-Query mit 2x LEFT JOIN
        // m = Alias für mitarbeiter
        // b = Alias für bereiche
        // a = Alias für abteilungen
        // LEFT JOIN = Auch Mitarbeiter ohne Bereich/Abteilung anzeigen
        $sql = "SELECT m.*, b.name as bereich_name, a.name as abteilung_name 
                FROM mitarbeiter m 
                LEFT JOIN bereiche b ON m.bereich_id=b.id 
                LEFT JOIN abteilungen a ON m.abteilung_id=a.id 
                ORDER BY m.nachname, m.vorname";
        
        // Query ausführen
        $stmt = $db->query($sql);
        
        // Alle Zeilen holen (oder leeres Array bei Fehler)
        return $stmt ? $db->fetchAll($stmt) : [];
    }
    
    /**
     * EINZELNEN MITARBEITER ABRUFEN (anhand ID)
     * 
     * Lädt einen spezifischen Mitarbeiter aus der Datenbank.
     * Wird verwendet für:
     * - Bearbeiten-Formular (Daten vorausfüllen)
     * - Warenkorb-Ansicht (Welcher Mitarbeiter bekommt Hardware/Software?)
     * 
     * @param int $id Die Mitarbeiter-ID
     * @return array|null Mitarbeiter-Array oder null wenn nicht gefunden
     */
    public static function getById($id) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL-Query mit WHERE-Bedingung
        // ? wird durch $id ersetzt (SQL-Injection-Schutz!)
        $sql = "SELECT * FROM mitarbeiter WHERE id=?";
        
        // Query ausführen mit Parameter $id
        $stmt = $db->query($sql, [$id]);
        
        // Ergebnisse holen
        $rows = $stmt ? $db->fetchAll($stmt) : [];
        
        // Ersten (und einzigen) Eintrag zurückgeben, oder null
        return $rows[0] ?? null;
    }
    
    /**
     * NEUEN MITARBEITER ERSTELLEN
     * 
     * Legt einen neuen Mitarbeiter in der Datenbank an.
     * 
     * WICHTIG:
     * - erstellt_am wird automatisch mit GETDATE() gesetzt (SQL Server)
     * - Das ist ein Timestamp, wann der Datensatz angelegt wurde
     * 
     * @param array $data Assoziatives Array mit Mitarbeiter-Daten:
     *                    - anrede (string): "Herr" / "Frau" / "Divers"
     *                    - typ (string): "Festanstellung" / "Zeitarbeit" / etc.
     *                    - vorname (string)
     *                    - nachname (string)
     *                    - email (string)
     *                    - telefon (string)
     *                    - bereich_id (int): ID aus bereiche-Tabelle
     *                    - abteilung_id (int): ID aus abteilungen-Tabelle
     *                    - stellenbeschreibung (string): Jobtitel
     *                    - startdatum (string): Format: YYYY-MM-DD
     *                    - prioritaet (string): "Normal" / "Hoch" / "Sehr Hoch"
     *                    - besondere_hinweise (string): Freitext (optional)
     *                    - status (string): "Offen" / "In Bearbeitung" / etc.
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function create($data) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL INSERT Statement vorbereiten
        // 13 Platzhalter (?) für 13 Werte
        // erstellt_am wird mit GETDATE() automatisch gesetzt (aktuelles Datum/Zeit)
        $sql = "INSERT INTO mitarbeiter (anrede, typ, vorname, nachname, email, telefon, bereich_id, abteilung_id, stellenbeschreibung, startdatum, prioritaet, besondere_hinweise, status, erstellt_am) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,GETDATE())";
        
        // SQL ausführen mit Parametern (in der richtigen Reihenfolge!)
        return $db->query($sql, [
            $data['anrede'], 
            $data['typ'], 
            $data['vorname'], 
            $data['nachname'], 
            $data['email'],
            $data['telefon'], 
            $data['bereich_id'], 
            $data['abteilung_id'], 
            $data['stellenbeschreibung'],
            $data['startdatum'], 
            $data['prioritaet'], 
            $data['besondere_hinweise'], 
            $data['status']
        ]);
    }
    
    /**
     * MITARBEITER AKTUALISIEREN
     * 
     * Aktualisiert einen bestehenden Mitarbeiter in der Datenbank.
     * 
     * HINWEIS:
     * - erstellt_am wird NICHT aktualisiert (bleibt unverändert)
     * - Nur die anderen Felder werden geändert
     * 
     * @param int $id Die Mitarbeiter-ID
     * @param array $data Assoziatives Array mit Mitarbeiter-Daten (siehe create())
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function update($id, $data) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL UPDATE Statement
        // Alle Felder werden aktualisiert (außer id und erstellt_am)
        $sql = "UPDATE mitarbeiter 
                SET anrede=?, typ=?, vorname=?, nachname=?, email=?, telefon=?, bereich_id=?, abteilung_id=?, stellenbeschreibung=?, startdatum=?, prioritaet=?, besondere_hinweise=?, status=? 
                WHERE id=?";
        
        // SQL ausführen mit Parametern (ID am Ende für WHERE-Bedingung!)
        return $db->query($sql, [
            $data['anrede'], 
            $data['typ'], 
            $data['vorname'], 
            $data['nachname'], 
            $data['email'],
            $data['telefon'], 
            $data['bereich_id'], 
            $data['abteilung_id'], 
            $data['stellenbeschreibung'],
            $data['startdatum'], 
            $data['prioritaet'], 
            $data['besondere_hinweise'], 
            $data['status'], 
            $id
        ]);
    }
    
    /**
     * MITARBEITER LÖSCHEN
     * 
     * Löscht einen Mitarbeiter aus der Datenbank.
     * 
     * WICHTIG:
     * - Dies ist ein "Hard Delete" (unwiederbringlich!)
     * - Besser wäre "Soft Delete" (Status auf "Archiviert" setzen)
     * - Kann Fehler geben wenn Mitarbeiter noch Hardware/Software zugewiesen hat
     *   (wegen Foreign Key Constraints mit CASCADE)
     * 
     * @param int $id Die Mitarbeiter-ID
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function delete($id) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL DELETE Statement
        $sql = "DELETE FROM mitarbeiter WHERE id=?";
        
        // SQL ausführen
        return $db->query($sql, [$id]);
    }
}

// ═══════════════════════════════════════════════════════════════
// ENDE DES MITARBEITER-MODELS
// 
// WICHTIGE KONZEPTE FÜR IHK-PRÜFUNG:
// 
// 1. JOINS:
//    - JOIN = Verknüpfung mehrerer Tabellen
//    - LEFT JOIN = Auch Zeilen ohne Verknüpfung anzeigen
//    - INNER JOIN = Nur Zeilen MIT Verknüpfung anzeigen
//    - Hier: LEFT JOIN für bereiche + abteilungen
// 
// 2. FOREIGN KEYS:
//    - bereich_id verweist auf bereiche.id
//    - abteilung_id verweist auf abteilungen.id
//    - Stellt Datenintegrität sicher (nur gültige IDs)
// 
// 3. GETDATE():
//    - SQL Server Funktion für aktuelles Datum/Zeit
//    - Andere DB: NOW() (MySQL), CURRENT_TIMESTAMP (PostgreSQL)
// 
// 4. ONBOARDING-PROZESS:
//    - Neuer Mitarbeiter wird angelegt
//    - Hardware/Software wird im Warenkorb zugewiesen
//    - Status ändert sich: Offen → In Bearbeitung → Abgeschlossen
//    - Mitarbeiter bekommt Hardware/Software zum Startdatum
// 
// ═══════════════════════════════════════════════════════════════
