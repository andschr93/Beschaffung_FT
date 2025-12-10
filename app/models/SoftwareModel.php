<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * SOFTWARE-MODEL - Verwaltung von Software-Stammdaten
 * ═══════════════════════════════════════════════════════════════
 * 
 * Dieses Model verwaltet die "software_stammdaten"-Tabelle.
 * Hier werden alle verfügbaren Software-Lizenzen gespeichert:
 * - Microsoft Office, Adobe Creative Cloud, E-Mail-Zugänge, etc.
 * 
 * WICHTIGE SPALTEN:
 * - name: Bezeichnung (z.B. "Microsoft Office 365")
 * - beschreibung: Weitere Infos (optional)
 * - lizenztyp: Art der Lizenz (z.B. "Subscription", "Perpetual")
 * - ist_standard: Wird automatisch zugewiesen? (1=ja, 0=nein)
 * - aktiv: Ist Software verfügbar? (1=ja, 0=nein)
 * 
 * ═══════════════════════════════════════════════════════════════
 */

// Benötigte Klassen laden
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Database.php';

class SoftwareModel extends Model {
    
    /**
     * ALLE SOFTWARE-LIZENZEN ABRUFEN
     * 
     * Lädt alle Software-Stammdaten aus der Datenbank.
     * Wird verwendet für die Software-Übersichtsseite.
     * 
     * @return array Array mit allen Software-Lizenzen (sortiert nach Name)
     */
    public static function getAll() {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL-Query: Alle Spalten, aufsteigend nach Name sortiert
        // ASC = Ascending (aufsteigend: A→Z)
        $sql = "SELECT * FROM software_stammdaten ORDER BY name ASC";
        
        // Query ausführen
        $stmt = $db->query($sql);
        
        // Alle Zeilen holen (oder leeres Array bei Fehler)
        return $stmt ? $db->fetchAll($stmt) : [];
    }
    
    /**
     * EINZELNE SOFTWARE-LIZENZ ABRUFEN (anhand ID)
     * 
     * Lädt eine spezifische Software-Lizenz aus der Datenbank.
     * Wird verwendet für:
     * - Bearbeiten-Formular (Daten vorausfüllen)
     * - Detail-Ansicht
     * 
     * @param int $id Die Software-ID
     * @return array|null Software-Array oder null wenn nicht gefunden
     */
    public static function getById($id) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL-Query mit WHERE-Bedingung
        // ? wird durch $id ersetzt (SQL-Injection-Schutz!)
        $sql = "SELECT * FROM software_stammdaten WHERE id = ?";
        
        // Query ausführen mit Parameter $id
        $stmt = $db->query($sql, [$id]);
        
        // Ergebnisse holen
        $rows = $stmt ? $db->fetchAll($stmt) : [];
        
        // Ersten (und einzigen) Eintrag zurückgeben, oder null
        // ?? = Null Coalescing Operator (PHP 7+)
        return $rows[0] ?? null;
    }
    
    /**
     * NEUE SOFTWARE-LIZENZ ERSTELLEN
     * 
     * Legt eine neue Software-Lizenz in der Datenbank an.
     * 
     * @param array $data Assoziatives Array mit Software-Daten:
     *                    - name (string): Bezeichnung
     *                    - beschreibung (string): Beschreibung (optional)
     *                    - lizenztyp (string): Lizenzart (z.B. "Subscription")
     *                    - ist_standard (int): 1=Standard, 0=optional
     *                    - aktiv (int): 1=aktiv, 0=inaktiv
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function create($data) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL INSERT Statement vorbereiten
        // 5 Platzhalter (?) für 5 Werte
        $sql = "INSERT INTO software_stammdaten (name, beschreibung, lizenztyp, ist_standard, aktiv) VALUES (?, ?, ?, ?, ?)";
        
        // SQL ausführen mit Parametern (in der richtigen Reihenfolge!)
        return $db->query($sql, [ 
            $data['name'], 
            $data['beschreibung'], 
            $data['lizenztyp'], 
            $data['ist_standard'], 
            $data['aktiv'] 
        ]);
    }
    
    /**
     * SOFTWARE-LIZENZ AKTUALISIEREN
     * 
     * Aktualisiert eine bestehende Software-Lizenz in der Datenbank.
     * 
     * @param int $id Die Software-ID
     * @param array $data Assoziatives Array mit Software-Daten (siehe create())
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function update($id, $data) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL UPDATE Statement
        // Alle Felder werden aktualisiert
        $sql = "UPDATE software_stammdaten SET name=?, beschreibung=?, lizenztyp=?, ist_standard=?, aktiv=? WHERE id = ?";
        
        // SQL ausführen mit Parametern (ID am Ende für WHERE-Bedingung!)
        return $db->query($sql, [ 
            $data['name'], 
            $data['beschreibung'], 
            $data['lizenztyp'], 
            $data['ist_standard'], 
            $data['aktiv'], 
            $id 
        ]);
    }
    
    /**
     * SOFTWARE-LIZENZ LÖSCHEN
     * 
     * Löscht eine Software-Lizenz aus der Datenbank.
     * 
     * WICHTIG:
     * - Dies ist ein "Hard Delete" (unwiederbringlich!)
     * - Besser wäre "Soft Delete" (nur aktiv auf 0 setzen)
     * - Kann Fehler geben wenn Software bereits zugewiesen wurde
     *   (wegen Foreign Key Constraints)
     * 
     * @param int $id Die Software-ID
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function delete($id) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL DELETE Statement
        $sql = "DELETE FROM software_stammdaten WHERE id = ?";
        
        // SQL ausführen
        return $db->query($sql, [$id]);
    }
}

// ═══════════════════════════════════════════════════════════════
// ENDE DES SOFTWARE-MODELS
// 
// ZUSAMMENFASSUNG:
// Dieses Model ist identisch aufgebaut wie HardwareModel.
// Beide verwenden das klassische CRUD-Pattern:
// - Create (Erstellen) → create()
// - Read (Lesen) → getAll(), getById()
// - Update (Aktualisieren) → update()
// - Delete (Löschen) → delete()
// 
// Unterschied zu HardwareModel:
// - Zusätzliche Spalte "beschreibung"
// - Zusätzliche Spalte "lizenztyp" (statt "kategorie")
// 
// ═══════════════════════════════════════════════════════════════
