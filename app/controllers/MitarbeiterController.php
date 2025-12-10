<?php
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/core/Database.php';
require_once BASE_PATH . '/app/controllers/AuthController.php';
require_once BASE_PATH . '/app/models/MitarbeiterModel.php';
require_once BASE_PATH . '/app/models/AbteilungenModel.php';
require_once BASE_PATH . '/app/models/BereicheModel.php';

class MitarbeiterController extends Controller {
    public function index() {
        AuthController::requireAuth();
        
        $list = MitarbeiterModel::getAll();
        $this->render('mitarbeiter/index', compact('list'));
    }
    public function create() {
        AuthController::requireAuth();
        
        $error = '';
        $abteilungen = AbteilungenModel::getAll();
        $bereiche = BereicheModel::getAll();
        $csrf_token = $this->getCsrfToken();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF-Validierung
            $this->validateCsrf();
            
            // Input-Validierung
            $vorname = trim($_POST['vorname'] ?? '');
            $nachname = trim($_POST['nachname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $abteilung_id = $_POST['abteilung_id'] ?? null;
            $bereich_id = $_POST['bereich_id'] ?? null;
            $startdatum = $_POST['startdatum'] ?? '';
            
            if (empty($vorname)) {
                $error = "Vorname darf nicht leer sein!";
                $this->render('mitarbeiter/create', compact('bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($nachname)) {
                $error = "Nachname darf nicht leer sein!";
                $this->render('mitarbeiter/create', compact('bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            // Email-Validierung
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (!$email) {
                $error = "Ungültige E-Mail-Adresse!";
                $this->render('mitarbeiter/create', compact('bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($abteilung_id) || !filter_var($abteilung_id, FILTER_VALIDATE_INT)) {
                $error = "Bitte wählen Sie eine Abteilung aus!";
                $this->render('mitarbeiter/create', compact('bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($bereich_id) || !filter_var($bereich_id, FILTER_VALIDATE_INT)) {
                $error = "Bitte wählen Sie einen Bereich aus!";
                $this->render('mitarbeiter/create', compact('bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($startdatum)) {
                $error = "Startdatum darf nicht leer sein!";
                $this->render('mitarbeiter/create', compact('bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            // Bereich-Abteilung-Beziehung prüfen
            $db = new Database();
            $sql = "SELECT COUNT(*) AS cnt FROM abteilungen WHERE id = ? AND bereich_id = ?";
            $stmt = $db->query($sql, [$abteilung_id, $bereich_id]);
            $row = $stmt ? $db->fetchAll($stmt)[0] : null;
            if (!$row || $row['cnt'] == 0) {
                $error = "Die gewählte Abteilung gehört nicht zum ausgewählten Bereich!";
                $this->render('mitarbeiter/create', compact('bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            $data = [
                'anrede' => trim($_POST['anrede'] ?? ''),
                'typ' => trim($_POST['typ'] ?? ''),
                'vorname' => $vorname,
                'nachname' => $nachname,
                'email' => $email,
                'telefon' => trim($_POST['telefon'] ?? ''),
                'bereich_id' => $bereich_id,
                'abteilung_id' => $abteilung_id,
                'stellenbeschreibung' => trim($_POST['stellenbeschreibung'] ?? ''),
                'startdatum' => $startdatum,
                'prioritaet' => trim($_POST['prioritaet'] ?? ''),
                'besondere_hinweise' => trim($_POST['besondere_hinweise'] ?? ''),
                'status' => trim($_POST['status'] ?? ''),
            ];
            
            MitarbeiterModel::create($data);
            $this->redirect('/mitarbeiter');
        }
        
        $this->render('mitarbeiter/create', compact('bereiche', 'abteilungen', 'error', 'csrf_token'));
    }
    public function edit() {
        AuthController::requireAuth();
        
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            die('Ungültige ID!');
        }
        
        $mitarbeiter = MitarbeiterModel::getById($id);
        if (!$mitarbeiter) {
            die('Mitarbeiter nicht gefunden!');
        }
        
        $error = '';
        $abteilungen = AbteilungenModel::getAll();
        $bereiche = BereicheModel::getAll();
        $csrf_token = $this->getCsrfToken();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF-Validierung
            $this->validateCsrf();
            
            // Input-Validierung
            $vorname = trim($_POST['vorname'] ?? '');
            $nachname = trim($_POST['nachname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $abteilung_id = $_POST['abteilung_id'] ?? null;
            $bereich_id = $_POST['bereich_id'] ?? null;
            $startdatum = $_POST['startdatum'] ?? '';
            
            if (empty($vorname)) {
                $error = "Vorname darf nicht leer sein!";
                $this->render('mitarbeiter/edit', compact('mitarbeiter', 'bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($nachname)) {
                $error = "Nachname darf nicht leer sein!";
                $this->render('mitarbeiter/edit', compact('mitarbeiter', 'bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            // Email-Validierung
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (!$email) {
                $error = "Ungültige E-Mail-Adresse!";
                $this->render('mitarbeiter/edit', compact('mitarbeiter', 'bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($abteilung_id) || !filter_var($abteilung_id, FILTER_VALIDATE_INT)) {
                $error = "Bitte wählen Sie eine Abteilung aus!";
                $this->render('mitarbeiter/edit', compact('mitarbeiter', 'bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($bereich_id) || !filter_var($bereich_id, FILTER_VALIDATE_INT)) {
                $error = "Bitte wählen Sie einen Bereich aus!";
                $this->render('mitarbeiter/edit', compact('mitarbeiter', 'bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($startdatum)) {
                $error = "Startdatum darf nicht leer sein!";
                $this->render('mitarbeiter/edit', compact('mitarbeiter', 'bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            // Bereich-Abteilung-Beziehung prüfen
            $db = new Database();
            $sql = "SELECT COUNT(*) AS cnt FROM abteilungen WHERE id = ? AND bereich_id = ?";
            $stmt = $db->query($sql, [$abteilung_id, $bereich_id]);
            $row = $stmt ? $db->fetchAll($stmt)[0] : null;
            if (!$row || $row['cnt'] == 0) {
                $error = "Die gewählte Abteilung gehört nicht zum ausgewählten Bereich!";
                $this->render('mitarbeiter/edit', compact('mitarbeiter', 'bereiche', 'abteilungen', 'error', 'csrf_token'));
                return;
            }
            
            $data = [
                'anrede' => trim($_POST['anrede'] ?? ''),
                'typ' => trim($_POST['typ'] ?? ''),
                'vorname' => $vorname,
                'nachname' => $nachname,
                'email' => $email,
                'telefon' => trim($_POST['telefon'] ?? ''),
                'bereich_id' => $bereich_id,
                'abteilung_id' => $abteilung_id,
                'stellenbeschreibung' => trim($_POST['stellenbeschreibung'] ?? ''),
                'startdatum' => $startdatum,
                'prioritaet' => trim($_POST['prioritaet'] ?? ''),
                'besondere_hinweise' => trim($_POST['besondere_hinweise'] ?? ''),
                'status' => trim($_POST['status'] ?? ''),
            ];
            
            MitarbeiterModel::update($id, $data);
            $this->redirect('/mitarbeiter');
        }
        
        $this->render('mitarbeiter/edit', compact('mitarbeiter', 'bereiche', 'abteilungen', 'error', 'csrf_token'));
    }
    public function delete() {
        AuthController::requireAuth();
        
        // DELETE nur per POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Methode nicht erlaubt. Bitte verwenden Sie das Formular.');
        }
        
        // CSRF-Validierung
        $this->validateCsrf();
        
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id) {
            MitarbeiterModel::delete($id);
        }
        
        $this->redirect('/mitarbeiter');
    }
}
