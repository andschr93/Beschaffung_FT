<?php
require_once BASE_PATH . '/app/controllers/AuthController.php';

class AdminController {
    public function index() {
        session_start();
        AuthController::checkRole([1]); // Nur Admins erlaubt (rolle_id=1)
        echo '<div style="padding:2em"><h1>ADMIN DASHBOARD</h1>';
        echo '<p>Login & Session erfolgreich!</p>';
        echo '<h3>Session-Info:</h3><pre>' . print_r($_SESSION['user'], true) . '</pre></div>';
    }
}
