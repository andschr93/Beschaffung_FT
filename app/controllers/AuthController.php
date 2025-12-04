<?php

require_once BASE_PATH . '/app/models/AuthModel.php';

class AuthController
{
    public function index()
    {
        // Login-Formular anzeigen
        $error = '';
        require BASE_PATH . '/app/views/auth/login.php';
    }

    public function login()
    {
        session_start();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = AuthModel::verifyUser($email, $password);

            if ($user) {
                // Session speichern
                $_SESSION['user'] = [
                    'id'       => $user['id'],
                    'rolle_id' => $user['rolle_id'],
                    'vorname'  => $user['vorname'],
                    'nachname' => $user['nachname'],
                ];

                // Routing nach Rolle (NEUER Router!)
                switch ($user['rolle_id']) {
                    case 1: $target = '/admin'; break;
                    case 2: $target = '/it'; break;
                    case 3: $target = '/vorzimmer'; break;
                    case 4: $target = '/personal'; break;
                    case 5: $target = '/hausmeister'; break;
                    default: $target = '/'; break;
                }

                header("Location: /Beschaffung_FT/public$target");
                exit();
            } else {
                $error = "Login fehlgeschlagen: Ungültige E-Mail oder Passwort!";
                require BASE_PATH . '/app/views/auth/login.php';
            }
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /Beschaffung_FT/public/login');
        exit();
    }

    // Middleware für Rollenprüfung (optional für Dashboard)
    public static function checkRole(array $allowedRoles)
    {
        session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rolle_id'], $allowedRoles)) {
            header("Location: /Beschaffung_FT/public/login");
            exit();
        }
    }
}
