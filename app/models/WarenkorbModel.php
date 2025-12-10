<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * WARENKORB-MODEL
 * ═══════════════════════════════════════════════════════════════
 * 
 * Dieses Model verwaltet die Zuordnung von Hardware und Software
 * zu Mitarbeitern. Es ist quasi der "Warenkorb" für die Beschaffung.
 * 
 * HAUPTFUNKTIONEN:
 * - Hardware zu Mitarbeiter hinzufügen
 * - Software zu Mitarbeiter hinzufügen
 * - Warenkorb anzeigen (was hat ein Mitarbeiter?)
 * - Artikel aus Warenkorb entfernen
 * 
 * ═══════════════════════════════════════════════════════════════
 */

require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Database.php';

class WarenkorbModel extends Model {
    
    // ═══════════════════════════════════════════════════════════
    // HARDWARE-FUNKTIONEN
    // ═══════════════════════════════════════════════════════════
    
    /**
     * Hardware zu einem Mitarbeiter hinzufügen
     * 
     * Fügt eine Hardware-Komponente dem Warenkorb eines Mitarbeiters hinzu.
     * Wenn die Hardware schon im Warenkorb ist, wird die Anzahl erhöht.
     * 
     * @param int $mitarbeiter_id ID des Mitarbeiters
     * @param int $hardware_id ID der Hardware
     * @param int $anzahl Anzahl (Standard: 1)
     * @param string $hinweis Optionale Bemerkung
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function addHardware($mitarbeiter_id, $hardware_id, $anzahl = 1, $hinweis = '') {
        $db = new Database();
        
        // SCHRITT 1: Prüfen ob diese Hardware schon im Warenkorb ist
        $checkSql = "SELECT id, anzahl FROM mitarbeiter_hardware 
                     WHERE mitarbeiter_id = ? AND hardware_id = ?";
        $checkStmt = $db->query($checkSql, [$mitarbeiter_id, $hardware_id]);
        $existing = $checkStmt ? $db->fetchAll($checkStmt) : [];
        
        if (count($existing) > 0) {
            // Hardware existiert bereits → Anzahl erhöhen
            $newAnzahl = $existing[0]['anzahl'] + $anzahl;
            $updateSql = "UPDATE mitarbeiter_hardware 
                          SET anzahl = ? 
                          WHERE id = ?";
            return $db->query($updateSql, [$newAnzahl, $existing[0]['id']]) !== false;
        } else {
            // Hardware ist noch nicht im Warenkorb → Neu einfügen
            $insertSql = "INSERT INTO mitarbeiter_hardware 
                          (mitarbeiter_id, hardware_id, anzahl, hinweis) 
                          VALUES (?, ?, ?, ?)";
            return $db->query($insertSql, [$mitarbeiter_id, $hardware_id, $anzahl, $hinweis]) !== false;
        }
    }
    
    /**
     * Alle Hardware-Artikel eines Mitarbeiters abrufen
     * 
     * Gibt ein Array mit allen Hardware-Komponenten zurück, die
     * für diesen Mitarbeiter bestellt wurden.
     * 
     * @param int $mitarbeiter_id ID des Mitarbeiters
     * @return array Array mit Hardware-Details
     */
    public static function getHardwareByMitarbeiter($mitarbeiter_id) {
        $db = new Database();
        
        // SQL mit JOIN um auch Hardware-Details zu bekommen
        $sql = "SELECT 
                    mh.id,
                    mh.hardware_id,
                    mh.anzahl,
                    mh.hinweis,
                    mh.erstellt_am,
                    hw.kategorie,
                    hw.name AS hardware_name,
                    hw.ist_standard
                FROM mitarbeiter_hardware mh
                INNER JOIN hardware_stammdaten hw ON mh.hardware_id = hw.id
                WHERE mh.mitarbeiter_id = ?
                ORDER BY mh.erstellt_am DESC";
        
        $stmt = $db->query($sql, [$mitarbeiter_id]);
        return $stmt ? $db->fetchAll($stmt) : [];
    }
    
    /**
     * Hardware aus Warenkorb entfernen
     * 
     * Löscht eine Hardware-Zuordnung komplett aus dem Warenkorb.
     * 
     * @param int $id ID des mitarbeiter_hardware Eintrags
     * @return bool True bei Erfolg
     */
    public static function removeHardware($id) {
        $db = new Database();
        $sql = "DELETE FROM mitarbeiter_hardware WHERE id = ?";
        return $db->query($sql, [$id]) !== false;
    }
    
    /**
     * Hardware-Anzahl aktualisieren
     * 
     * Ändert die Anzahl einer bereits hinzugefügten Hardware.
     * 
     * @param int $id ID des mitarbeiter_hardware Eintrags
     * @param int $anzahl Neue Anzahl
     * @return bool True bei Erfolg
     */
    public static function updateHardwareAnzahl($id, $anzahl) {
        $db = new Database();
        
        // Wenn Anzahl 0 oder kleiner → Eintrag löschen
        if ($anzahl <= 0) {
            return self::removeHardware($id);
        }
        
        $sql = "UPDATE mitarbeiter_hardware SET anzahl = ? WHERE id = ?";
        return $db->query($sql, [$anzahl, $id]) !== false;
    }
    
    // ═══════════════════════════════════════════════════════════
    // SOFTWARE-FUNKTIONEN (analog zu Hardware)
    // ═══════════════════════════════════════════════════════════
    
    /**
     * Software zu einem Mitarbeiter hinzufügen
     * 
     * Fügt eine Software-Lizenz dem Warenkorb eines Mitarbeiters hinzu.
     * Wenn die Software schon im Warenkorb ist, wird die Anzahl erhöht.
     * 
     * @param int $mitarbeiter_id ID des Mitarbeiters
     * @param int $software_id ID der Software
     * @param int $anzahl Anzahl Lizenzen (Standard: 1)
     * @param string $hinweis Optionale Bemerkung
     * @return bool True bei Erfolg, False bei Fehler
     */
    public static function addSoftware($mitarbeiter_id, $software_id, $anzahl = 1, $hinweis = '') {
        $db = new Database();
        
        // SCHRITT 1: Prüfen ob diese Software schon im Warenkorb ist
        $checkSql = "SELECT id, anzahl FROM mitarbeiter_software 
                     WHERE mitarbeiter_id = ? AND software_id = ?";
        $checkStmt = $db->query($checkSql, [$mitarbeiter_id, $software_id]);
        $existing = $checkStmt ? $db->fetchAll($checkStmt) : [];
        
        if (count($existing) > 0) {
            // Software existiert bereits → Anzahl erhöhen
            $newAnzahl = $existing[0]['anzahl'] + $anzahl;
            $updateSql = "UPDATE mitarbeiter_software 
                          SET anzahl = ? 
                          WHERE id = ?";
            return $db->query($updateSql, [$newAnzahl, $existing[0]['id']]) !== false;
        } else {
            // Software ist noch nicht im Warenkorb → Neu einfügen
            $insertSql = "INSERT INTO mitarbeiter_software 
                          (mitarbeiter_id, software_id, anzahl, hinweis) 
                          VALUES (?, ?, ?, ?)";
            return $db->query($insertSql, [$mitarbeiter_id, $software_id, $anzahl, $hinweis]) !== false;
        }
    }
    
    /**
     * Alle Software-Artikel eines Mitarbeiters abrufen
     * 
     * Gibt ein Array mit allen Software-Lizenzen zurück, die
     * für diesen Mitarbeiter bestellt wurden.
     * 
     * @param int $mitarbeiter_id ID des Mitarbeiters
     * @return array Array mit Software-Details
     */
    public static function getSoftwareByMitarbeiter($mitarbeiter_id) {
        $db = new Database();
        
        // SQL mit JOIN um auch Software-Details zu bekommen
        $sql = "SELECT 
                    ms.id,
                    ms.software_id,
                    ms.anzahl,
                    ms.hinweis,
                    ms.erstellt_am,
                    sw.name AS software_name,
                    sw.beschreibung,
                    sw.lizenztyp,
                    sw.ist_standard
                FROM mitarbeiter_software ms
                INNER JOIN software_stammdaten sw ON ms.software_id = sw.id
                WHERE ms.mitarbeiter_id = ?
                ORDER BY ms.erstellt_am DESC";
        
        $stmt = $db->query($sql, [$mitarbeiter_id]);
        return $stmt ? $db->fetchAll($stmt) : [];
    }
    
    /**
     * Software aus Warenkorb entfernen
     * 
     * Löscht eine Software-Zuordnung komplett aus dem Warenkorb.
     * 
     * @param int $id ID des mitarbeiter_software Eintrags
     * @return bool True bei Erfolg
     */
    public static function removeSoftware($id) {
        $db = new Database();
        $sql = "DELETE FROM mitarbeiter_software WHERE id = ?";
        return $db->query($sql, [$id]) !== false;
    }
    
    /**
     * Software-Anzahl aktualisieren
     * 
     * Ändert die Anzahl einer bereits hinzugefügten Software.
     * 
     * @param int $id ID des mitarbeiter_software Eintrags
     * @param int $anzahl Neue Anzahl
     * @return bool True bei Erfolg
     */
    public static function updateSoftwareAnzahl($id, $anzahl) {
        $db = new Database();
        
        // Wenn Anzahl 0 oder kleiner → Eintrag löschen
        if ($anzahl <= 0) {
            return self::removeSoftware($id);
        }
        
        $sql = "UPDATE mitarbeiter_software SET anzahl = ? WHERE id = ?";
        return $db->query($sql, [$anzahl, $id]) !== false;
    }
    
    // ═══════════════════════════════════════════════════════════
    // ZUSAMMENFASSUNGS-FUNKTIONEN
    // ═══════════════════════════════════════════════════════════
    
    /**
     * Kompletten Warenkorb eines Mitarbeiters abrufen
     * 
     * Gibt sowohl Hardware als auch Software zurück in einem Array.
     * 
     * @param int $mitarbeiter_id ID des Mitarbeiters
     * @return array ['hardware' => [...], 'software' => [...]]
     */
    public static function getWarenkorbByMitarbeiter($mitarbeiter_id) {
        return [
            'hardware' => self::getHardwareByMitarbeiter($mitarbeiter_id),
            'software' => self::getSoftwareByMitarbeiter($mitarbeiter_id)
        ];
    }
    
    /**
     * Prüft ob ein Mitarbeiter schon Artikel im Warenkorb hat
     * 
     * Nützlich um zu prüfen ob schon etwas bestellt wurde.
     * 
     * @param int $mitarbeiter_id ID des Mitarbeiters
     * @return bool True wenn Warenkorb nicht leer
     */
    public static function hasWarenkorb($mitarbeiter_id) {
        $hardware = self::getHardwareByMitarbeiter($mitarbeiter_id);
        $software = self::getSoftwareByMitarbeiter($mitarbeiter_id);
        
        return (count($hardware) > 0 || count($software) > 0);
    }
    
    /**
     * Kompletten Warenkorb eines Mitarbeiters leeren
     * 
     * Löscht alle Hardware- und Software-Zuordnungen.
     * VORSICHT: Nicht rückgängig zu machen!
     * 
     * @param int $mitarbeiter_id ID des Mitarbeiters
     * @return bool True bei Erfolg
     */
    public static function clearWarenkorb($mitarbeiter_id) {
        $db = new Database();
        
        // Hardware löschen
        $sql1 = "DELETE FROM mitarbeiter_hardware WHERE mitarbeiter_id = ?";
        $db->query($sql1, [$mitarbeiter_id]);
        
        // Software löschen
        $sql2 = "DELETE FROM mitarbeiter_software WHERE mitarbeiter_id = ?";
        $db->query($sql2, [$mitarbeiter_id]);
        
        return true;
    }
}

// ═══════════════════════════════════════════════════════════════
// VERWENDUNGS-BEISPIELE:
// ═══════════════════════════════════════════════════════════════
//
// Hardware hinzufügen:
// WarenkorbModel::addHardware($mitarbeiter_id, $hardware_id, 2, "Mit SSD");
//
// Hardware abrufen:
// $hardware = WarenkorbModel::getHardwareByMitarbeiter($mitarbeiter_id);
//
// Software hinzufügen:
// WarenkorbModel::addSoftware($mitarbeiter_id, $software_id, 1);
//
// Kompletten Warenkorb:
// $warenkorb = WarenkorbModel::getWarenkorbByMitarbeiter($mitarbeiter_id);
//
// ═══════════════════════════════════════════════════════════════

