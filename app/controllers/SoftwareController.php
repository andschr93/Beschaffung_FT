<?php
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/controllers/AuthController.php';
require_once BASE_PATH . '/app/models/SoftwareModel.php';

class SoftwareController extends Controller {
    
    public function index() {
        AuthController::requireAuth();
        $list = SoftwareModel::getAll();
        $this->render('software/index', compact('list'));
    }

    public function create() {
        AuthController::requireAuth();
        
        $error = '';
        $csrf_token = $this->getCsrfToken();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF-Validierung
            $this->validateCsrf();
            
            // Input-Validierung
            $name = trim($_POST['name'] ?? '');
            $beschreibung = trim($_POST['beschreibung'] ?? '');
            $lizenztyp = trim($_POST['lizenztyp'] ?? '');
            
            if (empty($name)) {
                $error = "Name darf nicht leer sein!";
                $this->render('software/create', compact('error', 'csrf_token'));
                return;
            }
            
            if (empty($beschreibung)) {
                $error = "Beschreibung darf nicht leer sein!";
                $this->render('software/create', compact('error', 'csrf_token'));
                return;
            }
            
            if (empty($lizenztyp)) {
                $error = "Lizenztyp darf nicht leer sein!";
                $this->render('software/create', compact('error', 'csrf_token'));
                return;
            }
            
            if (strlen($name) > 200) {
                $error = "Name darf maximal 200 Zeichen lang sein!";
                $this->render('software/create', compact('error', 'csrf_token'));
                return;
            }
            
            if (strlen($beschreibung) > 500) {
                $error = "Beschreibung darf maximal 500 Zeichen lang sein!";
                $this->render('software/create', compact('error', 'csrf_token'));
                return;
            }
            
            $data = [
                'name' => $name,
                'beschreibung' => $beschreibung,
                'lizenztyp' => $lizenztyp,
                'ist_standard' => isset($_POST['ist_standard']) ? 1 : 0,
                'aktiv' => isset($_POST['aktiv']) ? 1 : 0
            ];
            
            SoftwareModel::create($data);
            $this->redirect('/software');
        }
        
        $this->render('software/create', compact('error', 'csrf_token'));
    }

    public function edit() {
        AuthController::requireAuth();
        
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            die("Ungültige ID!");
        }
        
        $software = SoftwareModel::getById($id);
        if (!$software) {
            die("Software nicht gefunden!");
        }
        
        $error = '';
        $csrf_token = $this->getCsrfToken();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF-Validierung
            $this->validateCsrf();
            
            // Input-Validierung
            $name = trim($_POST['name'] ?? '');
            $beschreibung = trim($_POST['beschreibung'] ?? '');
            $lizenztyp = trim($_POST['lizenztyp'] ?? '');
            
            if (empty($name)) {
                $error = "Name darf nicht leer sein!";
                $this->render('software/edit', compact('software', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($beschreibung)) {
                $error = "Beschreibung darf nicht leer sein!";
                $this->render('software/edit', compact('software', 'error', 'csrf_token'));
                return;
            }
            
            if (empty($lizenztyp)) {
                $error = "Lizenztyp darf nicht leer sein!";
                $this->render('software/edit', compact('software', 'error', 'csrf_token'));
                return;
            }
            
            if (strlen($name) > 200) {
                $error = "Name darf maximal 200 Zeichen lang sein!";
                $this->render('software/edit', compact('software', 'error', 'csrf_token'));
                return;
            }
            
            $data = [
                'name' => $name,
                'beschreibung' => $beschreibung,
                'lizenztyp' => $lizenztyp,
                'ist_standard' => isset($_POST['ist_standard']) ? 1 : 0,
                'aktiv' => isset($_POST['aktiv']) ? 1 : 0
            ];
            
            SoftwareModel::update($id, $data);
            $this->redirect('/software');
        }
        
        $this->render('software/edit', compact('software', 'error', 'csrf_token'));
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
            SoftwareModel::delete($id);
        }
        
        $this->redirect('/software');
    }
}
