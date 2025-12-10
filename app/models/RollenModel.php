<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * ROLLEN-MODEL - Verwaltung von Benutzer-Rollen
 * ═══════════════════════════════════════════════════════════════
 * 
 * Dieses Model verwaltet die "rollen"-Tabelle.
 * Hier werden alle Benutzer-Rollen gespeichert:
 * - Administrator (ID: 1)
 * - IT-Abteilung (ID: 2)
 * - Vorzimmer/Personal (ID: 4)
 * - etc.
 * 
 * VERWENDUNG:
 * - Dropdown in User-Formularen (Welche Rolle hat der User?)
 * - Zugriffskontrolle (Welche Rollen dürfen was?)
 * 
 * WICHTIG:
 * - Nur getAll() Methode (Rollen werden meist nur gelesen)
 * - Neue Rollen würden direkt in der DB angelegt (selten)
 * 
 * ═══════════════════════════════════════════════════════════════
 */

// Benötigte Klassen laden
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Database.php';

class RollenModel extends Model {
    
    /**
     * ALLE ROLLEN ABRUFEN
     * 
     * Lädt alle Benutzer-Rollen aus der Datenbank.
     * Wird verwendet für:
     * - Dropdown-Menü im User-Formular
     * - Anzeige des Rollennamens in der User-Liste
     * 
     * @return array Array mit allen Rollen (id, name)
     */
    public static function getAll() {
        // Datenbankverbindung herstellen
        $db = new Database();
        
        // SQL-Query: Alle Rollen laden
        $sql = "SELECT * FROM rollen";
        
        // Query ausführen
        $stmt = $db->query($sql);
        
        // Alle Zeilen holen (oder leeres Array bei Fehler)
        return $stmt ? $db->fetchAll($stmt) : [];
    }
}

// ═══════════════════════════════════════════════════════════════
// ENDE DES ROLLEN-MODELS
// 
// HINWEIS:
// Dieses Model ist bewusst sehr einfach gehalten.
// Rollen sind normalerweise statisch (ändern sich selten).
// Deshalb gibt es hier keine create/update/delete Methoden.
// ═══════════════════════════════════════════════════════════════
