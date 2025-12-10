<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * WARENKORB-CONTROLLER
 * ═══════════════════════════════════════════════════════════════
 * 
 * Dieser Controller verwaltet alle Warenkorb-Aktionen:
 * - Hardware/Software hinzufügen
 * - Warenkorb anzeigen
 * - Artikel entfernen
 * - Mengen ändern
 * 
 * ═══════════════════════════════════════════════════════════════
 */

require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/controllers/AuthController.php';
require_once BASE_PATH . '/app/models/WarenkorbModel.php';
require_once BASE_PATH . '/app/models/MitarbeiterModel.php';
require_once BASE_PATH . '/app/models/HardwareModel.php';
require_once BASE_PATH . '/app/models/SoftwareModel.php';

class WarenkorbController extends Controller {
    
    /**
     * Warenkorb für einen Mitarbeiter anzeigen
     * 
     * Zeigt alle bereits ausgewählten Hardware- und Software-Artikel
     * für einen bestimmten Mitarbeiter an.
     * 
     * URL: /warenkorb?mitarbeiter_id=123
     */
    public function index() {
        // Nur eingeloggte User dürfen Warenkorb sehen
        AuthController::requireAuth();
        
        // Mitarbeiter-ID aus URL holen (z.B. ?mitarbeiter_id=5)
        $mitarbeiter_id = filter_var($_GET['mitarbeiter_id'] ?? null, FILTER_VALIDATE_INT);
        
        // Prüfen: Wurde eine gültige ID übergeben?
        if (!$mitarbeiter_id) {
            die('Ungültige Mitarbeiter-ID!');
        }
        
        // Mitarbeiter-Daten laden (für Anzeige des Namens)
        $mitarbeiter = MitarbeiterModel::getById($mitarbeiter_id);
        if (!$mitarbeiter) {
            die('Mitarbeiter nicht gefunden!');
        }
        
        // Warenkorb-Inhalt laden (Hardware + Software)
        $warenkorb = WarenkorbModel::getWarenkorbByMitarbeiter($mitarbeiter_id);
        
        // CSRF-Token für Formulare
        $csrf_token = $this->getCsrfToken();
        
        // View anzeigen
        $this->render('warenkorb/index', compact('mitarbeiter', 'warenkorb', 'csrf_token'));
    }
    
    /**
     * Hardware-Auswahl-Seite anzeigen
     * 
     * Zeigt eine Liste aller verfügbaren Hardware-Artikel,
     * die dem Mitarbeiter zugeordnet werden können.
     * 
     * URL: /warenkorb/hardware?mitarbeiter_id=123
     */
    public function hardware() {
        AuthController::requireAuth();
        
        $mitarbeiter_id = filter_var($_GET['mitarbeiter_id'] ?? null, FILTER_VALIDATE_INT);
        if (!$mitarbeiter_id) {
            die('Ungültige Mitarbeiter-ID!');
        }
        
        $mitarbeiter = MitarbeiterModel::getById($mitarbeiter_id);
        if (!$mitarbeiter) {
            die('Mitarbeiter nicht gefunden!');
        }
        
        // Alle verfügbaren Hardware-Artikel laden
        $hardware_liste = HardwareModel::getAll();
        
        // Bereits ausgewählte Hardware (für Highlighting)
        $bereits_im_warenkorb = WarenkorbModel::getHardwareByMitarbeiter($mitarbeiter_id);
        
        $csrf_token = $this->getCsrfToken();
        
        $this->render('warenkorb/hardware', compact(
            'mitarbeiter', 
            'hardware_liste', 
            'bereits_im_warenkorb',
            'csrf_token'
        ));
    }
    
    /**
     * Software-Auswahl-Seite anzeigen
     * 
     * Zeigt eine Liste aller verfügbaren Software-Artikel,
     * die dem Mitarbeiter zugeordnet werden können.
     * 
     * URL: /warenkorb/software?mitarbeiter_id=123
     */
    public function software() {
        AuthController::requireAuth();
        
        $mitarbeiter_id = filter_var($_GET['mitarbeiter_id'] ?? null, FILTER_VALIDATE_INT);
        if (!$mitarbeiter_id) {
            die('Ungültige Mitarbeiter-ID!');
        }
        
        $mitarbeiter = MitarbeiterModel::getById($mitarbeiter_id);
        if (!$mitarbeiter) {
            die('Mitarbeiter nicht gefunden!');
        }
        
        // Alle verfügbaren Software-Artikel laden
        $software_liste = SoftwareModel::getAll();
        
        // Bereits ausgewählte Software (für Highlighting)
        $bereits_im_warenkorb = WarenkorbModel::getSoftwareByMitarbeiter($mitarbeiter_id);
        
        $csrf_token = $this->getCsrfToken();
        
        $this->render('warenkorb/software', compact(
            'mitarbeiter', 
            'software_liste', 
            'bereits_im_warenkorb',
            'csrf_token'
        ));
    }
    
    /**
     * Hardware zum Warenkorb hinzufügen (POST)
     * 
     * Wird aufgerufen wenn User auf "Hinzufügen" klickt.
     * Fügt die Hardware dem Warenkorb hinzu und leitet zurück.
     */
    public function addHardware() {
        AuthController::requireAuth();
        
        // Nur POST-Requests erlaubt
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Methode nicht erlaubt');
        }
        
        // CSRF-Schutz
        $this->validateCsrf();
        
        // POST-Daten validieren
        $mitarbeiter_id = filter_var($_POST['mitarbeiter_id'] ?? null, FILTER_VALIDATE_INT);
        $hardware_id = filter_var($_POST['hardware_id'] ?? null, FILTER_VALIDATE_INT);
        $anzahl = filter_var($_POST['anzahl'] ?? 1, FILTER_VALIDATE_INT);
        $hinweis = trim($_POST['hinweis'] ?? '');
        
        if (!$mitarbeiter_id || !$hardware_id) {
            die('Ungültige Daten!');
        }
        
        // Anzahl muss mindestens 1 sein
        if ($anzahl < 1) $anzahl = 1;
        
        // Hardware hinzufügen
        $erfolg = WarenkorbModel::addHardware($mitarbeiter_id, $hardware_id, $anzahl, $hinweis);
        
        if ($erfolg) {
            // Erfolg → Zurück zur Hardware-Auswahl
            $this->redirect("/warenkorb/hardware?mitarbeiter_id=$mitarbeiter_id&success=1");
        } else {
            // Fehler
            $this->redirect("/warenkorb/hardware?mitarbeiter_id=$mitarbeiter_id&error=1");
        }
    }
    
    /**
     * Software zum Warenkorb hinzufügen (POST)
     * 
     * Analog zu addHardware(), aber für Software.
     */
    public function addSoftware() {
        AuthController::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Methode nicht erlaubt');
        }
        
        $this->validateCsrf();
        
        $mitarbeiter_id = filter_var($_POST['mitarbeiter_id'] ?? null, FILTER_VALIDATE_INT);
        $software_id = filter_var($_POST['software_id'] ?? null, FILTER_VALIDATE_INT);
        $anzahl = filter_var($_POST['anzahl'] ?? 1, FILTER_VALIDATE_INT);
        $hinweis = trim($_POST['hinweis'] ?? '');
        
        if (!$mitarbeiter_id || !$software_id) {
            die('Ungültige Daten!');
        }
        
        if ($anzahl < 1) $anzahl = 1;
        
        $erfolg = WarenkorbModel::addSoftware($mitarbeiter_id, $software_id, $anzahl, $hinweis);
        
        if ($erfolg) {
            $this->redirect("/warenkorb/software?mitarbeiter_id=$mitarbeiter_id&success=1");
        } else {
            $this->redirect("/warenkorb/software?mitarbeiter_id=$mitarbeiter_id&error=1");
        }
    }
    
    /**
     * Hardware aus Warenkorb entfernen (POST)
     * 
     * Löscht einen Hardware-Artikel aus dem Warenkorb.
     */
    public function removeHardware() {
        AuthController::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Methode nicht erlaubt');
        }
        
        $this->validateCsrf();
        
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $mitarbeiter_id = filter_var($_POST['mitarbeiter_id'] ?? null, FILTER_VALIDATE_INT);
        
        if ($id) {
            WarenkorbModel::removeHardware($id);
        }
        
        $this->redirect("/warenkorb?mitarbeiter_id=$mitarbeiter_id");
    }
    
    /**
     * Software aus Warenkorb entfernen (POST)
     * 
     * Analog zu removeHardware(), aber für Software.
     */
    public function removeSoftware() {
        AuthController::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Methode nicht erlaubt');
        }
        
        $this->validateCsrf();
        
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $mitarbeiter_id = filter_var($_POST['mitarbeiter_id'] ?? null, FILTER_VALIDATE_INT);
        
        if ($id) {
            WarenkorbModel::removeSoftware($id);
        }
        
        $this->redirect("/warenkorb?mitarbeiter_id=$mitarbeiter_id");
    }
    
    /**
     * Bestellung abschließen (POST)
     * 
     * Setzt den Status des Mitarbeiters auf "im Onboarding"
     * und speichert die Bestellung ab.
     */
    public function abschliessen() {
        AuthController::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Methode nicht erlaubt');
        }
        
        $this->validateCsrf();
        
        $mitarbeiter_id = filter_var($_POST['mitarbeiter_id'] ?? null, FILTER_VALIDATE_INT);
        
        if (!$mitarbeiter_id) {
            die('Ungültige Mitarbeiter-ID!');
        }
        
        // Prüfen: Hat der Mitarbeiter überhaupt etwas im Warenkorb?
        if (!WarenkorbModel::hasWarenkorb($mitarbeiter_id)) {
            $this->redirect("/warenkorb?mitarbeiter_id=$mitarbeiter_id&error=empty");
            return;
        }
        
        // Mitarbeiter-Status auf "im Onboarding" setzen
        $db = new Database();
        $sql = "UPDATE mitarbeiter SET status = 'im Onboarding' WHERE id = ?";
        $db->query($sql, [$mitarbeiter_id]);
        
        // Erfolg! → Zur Mitarbeiter-Übersicht
        $this->redirect("/mitarbeiter?success=bestellung");
    }
}

// ═══════════════════════════════════════════════════════════════
// ENDE WARENKORB-CONTROLLER
// ═══════════════════════════════════════════════════════════════

