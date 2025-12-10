<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * BEREICHE-MODEL - Verwaltung von Unternehmensbereichen
 * ═══════════════════════════════════════════════════════════════
 * 
 * Dieses Model verwaltet die "bereiche"-Tabelle.
 * Hier werden alle Unternehmensbereiche gespeichert:
 * - Verwaltung
 * - Produktion
 * - Vertrieb
 * - IT
 * - etc.
 * 
 * VERWENDUNG:
 * - Dropdown in Mitarbeiter-Formularen (Welchem Bereich gehört der Mitarbeiter an?)
 * - Organisatorische Struktur des Unternehmens
 * 
 * WICHTIG:
 * - Nur getAll() Methode (Bereiche werden meist nur gelesen)
 * - Neue Bereiche würden direkt in der DB angelegt (selten)
 * 
 * ═══════════════════════════════════════════════════════════════
 */

// Benötigte Klassen laden
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Database.php';

class BereicheModel extends Model {
    
    /**
     * ALLE BEREICHE ABRUFEN
     * 
     * Lädt alle Unternehmensbereiche aus der Datenbank.
     * Wird verwendet für:
     * - Dropdown-Menü im Mitarbeiter-Formular
     * - Anzeige des Bereichsnamens in der Mitarbeiter-Liste
     * 
     * @return array Array mit allen Bereichen (id, name)
     */
    public static function getAll() {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL-Query: Alle Bereiche laden
        $sql = "SELECT * FROM bereiche";
        
        // Query ausführen
        $stmt = $db->query($sql);
        
        // Alle Zeilen holen (oder leeres Array bei Fehler)
        return $stmt ? $db->fetchAll($stmt) : [];
    }
}

// ═══════════════════════════════════════════════════════════════
// ENDE DES BEREICHE-MODELS
// 
// HINWEIS:
// Dieses Model ist bewusst sehr einfach gehalten.
// Bereiche sind normalerweise statisch (ändern sich selten).
// Deshalb gibt es hier keine create/update/delete Methoden.
// ═══════════════════════════════════════════════════════════════
