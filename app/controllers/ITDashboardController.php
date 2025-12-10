<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * IT-DASHBOARD-CONTROLLER
 * ═══════════════════════════════════════════════════════════════
 * 
 * Dieser Controller ist speziell für die IT-Abteilung (Rolle 2).
 * Er zeigt ein Dashboard mit:
 * - Statistiken (Anzahl offene/in Bearbeitung/erledigte Anfragen)
 * - Dringende Mitarbeiter (Startdatum in den nächsten 7 Tagen)
 * - Schnellzugriffe zu Hardware/Software/Mitarbeiter
 * 
 * ZUGRIFF:
 * Nur für eingeloggte User mit IT-Rolle (rolle_id = 2 oder 5)
 * 
 * ═══════════════════════════════════════════════════════════════
 */

// Benötigte Klassen laden
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/controllers/AuthController.php';
require_once BASE_PATH . '/app/models/MitarbeiterModel.php';
require_once BASE_PATH . '/app/core/Database.php';

class ITDashboardController extends Controller {
    
    /**
     * IT-DASHBOARD ANZEIGEN
     * 
     * Diese Methode zeigt das Haupt-Dashboard für IT-Mitarbeiter.
     * 
     * ABLAUF:
     * 1. Prüfen ob User eingeloggt ist
     * 2. Statistiken berechnen (SQL-Queries)
     * 3. Dringende Mitarbeiter laden (Startdatum bald)
     * 4. Dashboard-View anzeigen
     */
    public function index() {
        // ═══ ZUGRIFFSKONTROLLE ═══
        // Nur eingeloggte User dürfen hier rein
        AuthController::requireAuth();
        
        // Optional: Nur IT-Rollen erlauben (Rolle 2 + 5)
        // AuthController::requireRole([2, 5]);
        
        // ═══ STATISTIKEN BERECHNEN ═══
        $stats = $this->getStatistics();
        
        // ═══ DRINGENDE MITARBEITER LADEN ═══
        // Mitarbeiter, die in den nächsten 7 Tagen anfangen
        $urgentMitarbeiter = $this->getUrgentMitarbeiter();
        
        // ═══ LETZTE AKTIVITÄTEN (optional) ═══
        $recentActivities = $this->getRecentActivities();
        
        // ═══ VIEW ANZEIGEN ═══
        $this->render('it-dashboard/index', [
            'stats' => $stats,
            'urgentMitarbeiter' => $urgentMitarbeiter,
            'recentActivities' => $recentActivities
        ]);
    }
    
    /**
     * STATISTIKEN BERECHNEN
     * 
     * Berechnet verschiedene Kennzahlen für das Dashboard:
     * - Anzahl Mitarbeiter nach Status
     * - Anzahl Hardware-Artikel
     * - Anzahl Software-Lizenzen
     * 
     * @return array Assoziatives Array mit Statistiken
     */
    private function getStatistics() {
        $db = new Database();
        
        // ═══ MITARBEITER-STATISTIKEN ═══
        // Anzahl nach Status (Offen, In Bearbeitung, Abgeschlossen)
        
        // Status: "Offen" (noch nicht angefangen)
        $sqlOffen = "SELECT COUNT(*) as count FROM mitarbeiter WHERE status = 'Offen'";
        $stmtOffen = $db->query($sqlOffen);
        $rowsOffen = $db->fetchAll($stmtOffen);
        $countOffen = $rowsOffen[0]['count'] ?? 0;
        
        // Status: "In Bearbeitung" (wird gerade bearbeitet)
        $sqlInProgress = "SELECT COUNT(*) as count FROM mitarbeiter WHERE status = 'In Bearbeitung'";
        $stmtInProgress = $db->query($sqlInProgress);
        $rowsInProgress = $db->fetchAll($stmtInProgress);
        $countInProgress = $rowsInProgress[0]['count'] ?? 0;
        
        // Status: "Abgeschlossen" (fertig)
        $sqlDone = "SELECT COUNT(*) as count FROM mitarbeiter WHERE status = 'Abgeschlossen'";
        $stmtDone = $db->query($sqlDone);
        $rowsDone = $db->fetchAll($stmtDone);
        $countDone = $rowsDone[0]['count'] ?? 0;
        
        // ═══ HARDWARE/SOFTWARE-STATISTIKEN ═══
        // Anzahl verfügbare Hardware-Artikel (aktiv = 1)
        $sqlHardware = "SELECT COUNT(*) as count FROM hardware_stammdaten WHERE aktiv = 1";
        $stmtHardware = $db->query($sqlHardware);
        $rowsHardware = $db->fetchAll($stmtHardware);
        $countHardware = $rowsHardware[0]['count'] ?? 0;
        
        // Anzahl verfügbare Software-Lizenzen (aktiv = 1)
        $sqlSoftware = "SELECT COUNT(*) as count FROM software_stammdaten WHERE aktiv = 1";
        $stmtSoftware = $db->query($sqlSoftware);
        $rowsSoftware = $db->fetchAll($stmtSoftware);
        $countSoftware = $rowsSoftware[0]['count'] ?? 0;
        
        // ═══ RÜCKGABE ═══
        return [
            'mitarbeiter_offen' => $countOffen,
            'mitarbeiter_in_bearbeitung' => $countInProgress,
            'mitarbeiter_abgeschlossen' => $countDone,
            'mitarbeiter_gesamt' => $countOffen + $countInProgress + $countDone,
            'hardware_verfuegbar' => $countHardware,
            'software_verfuegbar' => $countSoftware
        ];
    }
    
    /**
     * DRINGENDE MITARBEITER LADEN
     * 
     * Lädt Mitarbeiter, die bald anfangen (in den nächsten 7 Tagen).
     * Diese sollten prioritär bearbeitet werden!
     * 
     * @return array Liste mit dringenden Mitarbeitern
     */
    private function getUrgentMitarbeiter() {
        $db = new Database();
        
        // SQL-Query: Mitarbeiter mit Startdatum in den nächsten 7 Tagen
        // DATEADD(day, 7, GETDATE()) = Heute + 7 Tage
        // Status != 'Abgeschlossen' = Nur offene/in Bearbeitung
        $sql = "SELECT 
                    id, 
                    vorname, 
                    nachname, 
                    startdatum, 
                    status, 
                    prioritaet,
                    DATEDIFF(day, GETDATE(), startdatum) as tage_bis_start
                FROM mitarbeiter 
                WHERE startdatum <= DATEADD(day, 7, GETDATE())
                  AND startdatum >= GETDATE()
                  AND status != 'Abgeschlossen'
                ORDER BY startdatum ASC, prioritaet DESC";
        
        $stmt = $db->query($sql);
        return $stmt ? $db->fetchAll($stmt) : [];
    }
    
    /**
     * LETZTE AKTIVITÄTEN LADEN
     * 
     * Zeigt die letzten 5 neu angelegten Mitarbeiter.
     * Gibt einen Überblick über aktuelle Vorgänge.
     * 
     * @return array Liste mit letzten Aktivitäten
     */
    private function getRecentActivities() {
        $db = new Database();
        
        // SQL-Query: Die letzten 5 neu angelegten Mitarbeiter
        // TOP 5 = nur die ersten 5 Ergebnisse
        // ORDER BY erstellt_am DESC = neueste zuerst
        $sql = "SELECT TOP 5
                    id,
                    vorname,
                    nachname,
                    status,
                    erstellt_am
                FROM mitarbeiter
                ORDER BY erstellt_am DESC";
        
        $stmt = $db->query($sql);
        return $stmt ? $db->fetchAll($stmt) : [];
    }
}

// ═══════════════════════════════════════════════════════════════
// ENDE DES IT-DASHBOARD-CONTROLLERS
// 
// WICHTIGE KONZEPTE FÜR IHK-PRÜFUNG:
// 
// 1. DASHBOARD-PATTERN:
//    - Zentraler Überblick über wichtige Kennzahlen
//    - Schnellzugriffe für häufige Aufgaben
//    - Priorisierung (dringende Vorgänge zuerst)
// 
// 2. SQL-AGGREGATION:
//    - COUNT() = Anzahl Zeilen zählen
//    - DATEDIFF() = Differenz zwischen zwei Daten
//    - DATEADD() = Datum + X Tage
//    - TOP N = Nur die ersten N Ergebnisse
// 
// 3. ROLLENBASIERTE DASHBOARDS:
//    - Jede Rolle bekommt eigenes Dashboard
//    - IT sieht andere Infos als Admin oder Vorzimmer
//    - Zeigt professionelle Systemarchitektur
// 
// ═══════════════════════════════════════════════════════════════

