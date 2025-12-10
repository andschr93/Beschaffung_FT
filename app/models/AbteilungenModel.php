<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * ABTEILUNGEN-MODEL - Verwaltung von Abteilungen
 * ═══════════════════════════════════════════════════════════════
 * 
 * Dieses Model verwaltet die "abteilungen"-Tabelle.
 * Hier werden alle Abteilungen gespeichert:
 * - Buchhaltung
 * - Einkauf
 * - Verkauf
 * - Marketing
 * - HR / Personalwesen
 * - etc.
 * 
 * VERWENDUNG:
 * - Dropdown in Mitarbeiter-Formularen (Welcher Abteilung gehört der Mitarbeiter an?)
 * - Organisatorische Struktur des Unternehmens (feiner als Bereiche)
 * 
 * VERHÄLTNIS ZU BEREICHEN:
 * - Bereiche = Grobe Unterteilung (z.B. "Verwaltung")
 * - Abteilungen = Feine Unterteilung (z.B. "Buchhaltung", "Einkauf")
 * - Ein Bereich kann mehrere Abteilungen haben
 * 
 * WICHTIG:
 * - Nur getAll() Methode (Abteilungen werden meist nur gelesen)
 * - Neue Abteilungen würden direkt in der DB angelegt (selten)
 * 
 * ═══════════════════════════════════════════════════════════════
 */

// Benötigte Klassen laden
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Database.php';

class AbteilungenModel extends Model {
    
    /**
     * ALLE ABTEILUNGEN ABRUFEN
     * 
     * Lädt alle Abteilungen aus der Datenbank.
     * Wird verwendet für:
     * - Dropdown-Menü im Mitarbeiter-Formular
     * - Anzeige des Abteilungsnamens in der Mitarbeiter-Liste
     * 
     * @return array Array mit allen Abteilungen (id, name)
     */
    public static function getAll() {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL-Query: Alle Abteilungen laden
        $sql = "SELECT * FROM abteilungen";
        
        // Query ausführen
        $stmt = $db->query($sql);
        
        // Alle Zeilen holen (oder leeres Array bei Fehler)
        return $stmt ? $db->fetchAll($stmt) : [];
    }
}

// ═══════════════════════════════════════════════════════════════
// ENDE DES ABTEILUNGEN-MODELS
// 
// HINWEIS:
// Dieses Model ist bewusst sehr einfach gehalten.
// Abteilungen sind normalerweise statisch (ändern sich selten).
// Deshalb gibt es hier keine create/update/delete Methoden.
// ═══════════════════════════════════════════════════════════════
