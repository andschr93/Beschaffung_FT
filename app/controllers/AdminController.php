<?php
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/controllers/AuthController.php';

class AdminController extends Controller {
    public function index() {
        AuthController::requireRole([1]); // Nur Admins erlaubt (rolle_id=1)
        
        $user = $_SESSION['user'];
        
        $this->render('admin/dashboard', compact('user'));
    }
}
