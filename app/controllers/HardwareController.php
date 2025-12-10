<?php

require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/controllers/AuthController.php';
require_once BASE_PATH . '/app/models/HardwareModel.php';

class HardwareController extends Controller {
    
    public function index() {
        AuthController::requireAuth();
        $list = HardwareModel::getAll();
        $this->render('hardware/index', compact('list'));
    }

    public function create() {
        AuthController::requireAuth();
        
        $error = '';
        $csrf_token = $this->getCsrfToken();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF-Validierung
            $this->validateCsrf();
            
            // Input-Validierung
            $kategorie = trim($_POST['kategorie'] ?? '');
            $name = trim($_POST['name'] ?? '');
            
            if (empty($kategorie)) {
                $error = "Kategorie darf nicht leer sein!";
                $this->render('hardware/create', compact('error', 'csrf_token'));
                return;
            }
            
            if (empty($name)) {
                $error = "Name darf nicht leer sein!";
                $this->render('hardware/create', compact('error', 'csrf_token'));
                return;
            }
            
            if (strlen($name) > 200) {
                $error = "Name darf maximal 200 Zeichen lang sein!";
                $this->render('hardware/create', compact('error', 'csrf_token'));
                return;
            }
            
            if (strlen($kategorie) > 100) {
                $error = "Kategorie darf maximal 100 Zeichen lang sein!";
                $this->render('hardware/create', compact('error', 'csrf_token'));
                return;
            }
            
            $data = [
                'kategorie' => $kategorie,
                'name' => $name,
                'ist_standard' => isset($_POST['ist_standard']) ? 1 : 0,
                'aktiv' => isset($_POST['aktiv']) ? 1 : 0
            ];
            
            HardwareModel::create($data);
            $this->redirect('/hardware');
        }
        
        $this->render('hardware/create', compact('error', 'csrf_token'));
    }

    public function edit() {
        AuthController::requireAuth();
        
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            die("Ungültige ID!");
        }
        
        $hardware = HardwareModel::getById($id);
        if (!$hardware) {
            die("Hardware nicht gefunden!");
        }
        
        $error = '';
        $csrf_token = $this->getCsrfToken();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF-Validierung
            $this->validateCsrf();
            
            // Input-Validierung
            $kategorie = trim($_POST['kategorie'] ?? '');
            $name = trim($_POST['name'] ?? '');
            
            if (empty($kategorie)) {
                $error = "Kategorie darf nicht leer sein!";
                $this->render('hardware/edit', compact('hardware', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($name)) {
                $error = "Name darf nicht leer sein!";
                $this->render('hardware/edit', compact('hardware', 'error', 'csrf_token'));
                return;
            }
            
            if (strlen($name) > 200) {
                $error = "Name darf maximal 200 Zeichen lang sein!";
                $this->render('hardware/edit', compact('hardware', 'error', 'csrf_token'));
                return;
            }
            
            $data = [
                'kategorie' => $kategorie,
                'name' => $name,
                'ist_standard' => isset($_POST['ist_standard']) ? 1 : 0,
                'aktiv' => isset($_POST['aktiv']) ? 1 : 0
            ];
            
            HardwareModel::update($id, $data);
            $this->redirect('/hardware');
        }
        
        $this->render('hardware/edit', compact('hardware', 'error', 'csrf_token'));
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
            HardwareModel::delete($id);
        }
        
        $this->redirect('/hardware');
    }
}
