<?php
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/models/UserModel.php';
require_once BASE_PATH . '/app/models/RollenModel.php';
require_once BASE_PATH . '/app/controllers/AuthController.php';

class UserController extends Controller {
    
    private function requireAdminOrVorzimmer() {
        $rolle = $_SESSION['user']['rolle_id'] ?? null;
        if (!in_array($rolle, [1, 3])) { // Admin=1, Vorzimmer=3
            http_response_code(403);
            die('Nicht genügend Rechte. Nur Admins und Vorzimmer haben Zugriff.');
        }
    }
    
    public function index() {
        AuthController::requireAuth();
        $this->requireAdminOrVorzimmer();
        
        $list = UserModel::getAll();
        $this->render('user/index', compact('list'));
    }
    
    public function create() {
        AuthController::requireAuth();
        $this->requireAdminOrVorzimmer();
        
        $error = '';
        $rollen = RollenModel::getAll();
        $csrf_token = $this->getCsrfToken();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF-Validierung
            $this->validateCsrf();
            
            // Input-Validierung
            $vorname = trim($_POST['vorname'] ?? '');
            $nachname = trim($_POST['nachname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $rolle_id = $_POST['rolle_id'] ?? null;
            $password = $_POST['password'] ?? '';
            
            if (empty($vorname)) {
                $error = "Vorname darf nicht leer sein!";
                $this->render('user/create', compact('rollen', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($nachname)) {
                $error = "Nachname darf nicht leer sein!";
                $this->render('user/create', compact('rollen', 'error', 'csrf_token'));
                return;
            }
            
            // Email-Validierung
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (!$email) {
                $error = "Ungültige E-Mail-Adresse!";
                $this->render('user/create', compact('rollen', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($rolle_id) || !filter_var($rolle_id, FILTER_VALIDATE_INT)) {
                $error = "Bitte wählen Sie eine Rolle aus!";
                $this->render('user/create', compact('rollen', 'error', 'csrf_token'));
                return;
            }
            
            // Passwort-Validierung
            if (empty($password)) {
                $error = "Passwort darf nicht leer sein!";
                $this->render('user/create', compact('rollen', 'error', 'csrf_token'));
                return;
            }
            
            if (strlen($password) < 8) {
                $error = "Passwort muss mindestens 8 Zeichen lang sein!";
                $this->render('user/create', compact('rollen', 'error', 'csrf_token'));
                return;
            }
            
            $data = [
                'vorname' => $vorname,
                'nachname' => $nachname,
                'email' => $email,
                'rolle_id' => $rolle_id,
                'aktiv' => isset($_POST['aktiv']) ? 1 : 0,
                'password' => $password
            ];
            
            UserModel::create($data);
            $this->redirect('/users');
        }
        
        $this->render('user/create', compact('rollen', 'error', 'csrf_token'));
    }
    
    public function edit() {
        AuthController::requireAuth();
        $this->requireAdminOrVorzimmer();
        
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            die('Ungültige ID!');
        }
        
        $user = UserModel::getById($id);
        if (!$user) {
            die('Benutzer nicht gefunden!');
        }
        
        $error = '';
        $rollen = RollenModel::getAll();
        $csrf_token = $this->getCsrfToken();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF-Validierung
            $this->validateCsrf();
            
            // Input-Validierung
            $vorname = trim($_POST['vorname'] ?? '');
            $nachname = trim($_POST['nachname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $rolle_id = $_POST['rolle_id'] ?? null;
            $password = $_POST['password'] ?? '';
            
            if (empty($vorname)) {
                $error = "Vorname darf nicht leer sein!";
                $this->render('user/edit', compact('user', 'rollen', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($nachname)) {
                $error = "Nachname darf nicht leer sein!";
                $this->render('user/edit', compact('user', 'rollen', 'error', 'csrf_token'));
                return;
            }
            
            // Email-Validierung
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (!$email) {
                $error = "Ungültige E-Mail-Adresse!";
                $this->render('user/edit', compact('user', 'rollen', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($rolle_id) || !filter_var($rolle_id, FILTER_VALIDATE_INT)) {
                $error = "Bitte wählen Sie eine Rolle aus!";
                $this->render('user/edit', compact('user', 'rollen', 'error', 'csrf_token'));
                return;
            }
            
            // Passwort-Validierung (nur wenn gesetzt)
            if (!empty($password) && strlen($password) < 8) {
                $error = "Passwort muss mindestens 8 Zeichen lang sein!";
                $this->render('user/edit', compact('user', 'rollen', 'error', 'csrf_token'));
                return;
            }
            
            $data = [
                'vorname' => $vorname,
                'nachname' => $nachname,
                'email' => $email,
                'rolle_id' => $rolle_id,
                'aktiv' => isset($_POST['aktiv']) ? 1 : 0,
                'password' => $password // Leer = kein Update
            ];
            
            UserModel::update($id, $data);
            $this->redirect('/users');
        }
        
        $this->render('user/edit', compact('user', 'rollen', 'error', 'csrf_token'));
    }
    
    public function setActive() {
        AuthController::requireAuth();
        $this->requireAdminOrVorzimmer();
        
        // Nur per POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Methode nicht erlaubt.');
        }
        
        // CSRF-Validierung
        $this->validateCsrf();
        
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $status = isset($_POST['active']) ? ($_POST['active'] == 1 ? 1 : 0) : null;
        
        if ($id !== null && $status !== null) {
            UserModel::setActive($id, $status);
        }
        
        $this->redirect('/users');
    }
}
