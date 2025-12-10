<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * HARDWARE-MODEL - Verwaltung von Hardware-Stammdaten
 * ═══════════════════════════════════════════════════════════════
 * 
 * Dieses Model verwaltet die "hardware_stammdaten"-Tabelle.
 * Hier werden alle verfügbaren Hardware-Artikel gespeichert:
 * - Monitore, Tastaturen, Mäuse, Laptops, etc.
 * 
 * WICHTIGE SPALTEN:
 * - name: Bezeichnung (z.B. "Dell Monitor 24 Zoll")
 * - kategorie: Typ (z.B. "Monitor", "Tastatur")
 * - ist_standard: Wird automatisch zugewiesen? (1=ja, 0=nein)
 * - aktiv: Ist Artikel verfügbar? (1=ja, 0=nein)
 * 
 * ═══════════════════════════════════════════════════════════════
 */

// Benötigte Klassen laden
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Database.php';

class HardwareModel extends Model {
    
    /**
     * ALLE HARDWARE-ARTIKEL ABRUFEN
     * 
     * Lädt alle Hardware-Stammdaten aus der Datenbank.
     * Wird verwendet für die Hardware-Übersichtsseite.
     * 
     * @return array Array mit allen Hardware-Artikeln
     */
    public static function getAll() {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL-Query: Alle Spalten, sortiert nach Name
        $sql = "SELECT * FROM hardware_stammdaten ORDER BY name";
        
        // Query ausführen
        $stmt = $db->query($sql);
        
        // Alle Zeilen holen (oder leeres Array bei Fehler)
        return $stmt ? $db->fetchAll($stmt) : [];
    }
    
    /**
     * EINZELNEN HARDWARE-ARTIKEL ABRUFEN (anhand ID)
     * 
     * Lädt einen spezifischen Hardware-Artikel aus der Datenbank.
     * Wird verwendet für:
     * - Bearbeiten-Formular (Daten vorausfüllen)
     * - Detail-Ansicht
     * 
     * @param int $id Die Hardware-ID
     * @return array|null Hardware-Array oder null wenn nicht gefunden
     */
    public static function getById($id) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL-Query mit WHERE-Bedingung
        // ? wird durch $id ersetzt (SQL-Injection-Schutz!)
        $sql = "SELECT * FROM hardware_stammdaten WHERE id = ?";
        
        // Query ausführen mit Parameter $id
        $stmt = $db->query($sql, [$id]);
        
        // Ergebnisse holen
        $rows = $stmt ? $db->fetchAll($stmt) : [];
        
        // Ersten (und einzigen) Eintrag zurückgeben, oder null
        return $rows[0] ?? null;
    }
    
    /**
     * NEUEN HARDWARE-ARTIKEL ERSTELLEN
     * 
     * Legt einen neuen Hardware-Artikel in der Datenbank an.
     * 
     * @param array $data Assoziatives Array mit Hardware-Daten:
     *                    - name (string): Bezeichnung
     *                    - kategorie (string): Typ (z.B. "Monitor")
     *                    - ist_standard (int): 1=Standard, 0=optional
     *                    - aktiv (int): 1=aktiv, 0=inaktiv
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function create($data) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL INSERT Statement vorbereiten
        // 4 Platzhalter (?) für 4 Werte
        $sql = "INSERT INTO hardware_stammdaten (name, kategorie, ist_standard, aktiv) VALUES (?, ?, ?, ?)";
        
        // SQL ausführen mit Parametern
        return $db->query($sql, [ 
            $data['name'], 
            $data['kategorie'], 
            $data['ist_standard'], 
            $data['aktiv'] 
        ]);
    }
    
    /**
     * HARDWARE-ARTIKEL AKTUALISIEREN
     * 
     * Aktualisiert einen bestehenden Hardware-Artikel in der Datenbank.
     * 
     * @param int $id Die Hardware-ID
     * @param array $data Assoziatives Array mit Hardware-Daten (siehe create())
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function update($id, $data) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL UPDATE Statement
        // Alle Felder werden aktualisiert
        $sql = "UPDATE hardware_stammdaten SET name=?, kategorie=?, ist_standard=?, aktiv=? WHERE id = ?";
        
        // SQL ausführen mit Parametern (ID am Ende für WHERE-Bedingung!)
        return $db->query($sql, [ 
            $data['name'], 
            $data['kategorie'], 
            $data['ist_standard'], 
            $data['aktiv'], 
            $id 
        ]);
    }
    
    /**
     * HARDWARE-ARTIKEL LÖSCHEN
     * 
     * Löscht einen Hardware-Artikel aus der Datenbank.
     * 
     * WICHTIG:
     * - Dies ist ein "Hard Delete" (unwiederbringlich!)
     * - Besser wäre "Soft Delete" (nur aktiv auf 0 setzen)
     * - Kann Fehler geben wenn Hardware bereits zugewiesen wurde
     *   (wegen Foreign Key Constraints)
     * 
     * @param int $id Die Hardware-ID
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function delete($id) {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL DELETE Statement
        $sql = "DELETE FROM hardware_stammdaten WHERE id = ?";
        
        // SQL ausführen
        return $db->query($sql, [$id]);
    }
}

// ═══════════════════════════════════════════════════════════════
// ENDE DES HARDWARE-MODELS
// 
// ZUSAMMENFASSUNG:
// Dieses Model ist sehr einfach aufgebaut (klassisches CRUD-Model).
// Es zeigt die Grundstruktur aller Models in diesem Projekt:
// - getAll() → Alle Datensätze
// - getById() → Einzelner Datensatz
// - create() → Neuer Datensatz
// - update() → Datensatz ändern
// - delete() → Datensatz löschen
// 
// ═══════════════════════════════════════════════════════════════
