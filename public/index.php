
<?php
/**
 * Beschaffungs- und Onboarding-System
 * IHK Prüfungsprojekt
 */

// Basis-Pfad definieren
define('BASE_PATH', dirname(__DIR__));

// Konfiguration laden (enthält Session-Start, Error-Handling, BASE_URL)
require_once BASE_PATH . '/config/config.php';

// Zentraler Error-Handler
set_exception_handler(function($exception) {
    http_response_code(500);
    error_log("Exception: " . $exception->getMessage() . " in " . $exception->getFile() . ":" . $exception->getLine());
    
    if (APP_DEBUG) {
        echo "<h1>Fehler aufgetreten</h1>";
        echo "<p><strong>Nachricht:</strong> " . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Datei:</strong> " . htmlspecialchars($exception->getFile(), ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Zeile:</strong> " . $exception->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($exception->getTraceAsString(), ENT_QUOTES, 'UTF-8') . "</pre>";
    } else {
        echo "<h1>Ein Fehler ist aufgetreten</h1>";
        echo "<p>Bitte versuchen Sie es später erneut oder kontaktieren Sie den Administrator.</p>";
    }
});

set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Router laden
require_once BASE_PATH . '/app/core/Router.php';

$router = new Router();

// AUTH
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@index');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// ADMIN
$router->get('/admin', 'AdminController@index');

// IT-DASHBOARD
$router->get('/it-dashboard', 'ITDashboardController@index');

// HARDWARE
$router->get('/hardware', 'HardwareController@index');
$router->get('/hardware/create', 'HardwareController@create');
$router->post('/hardware/create', 'HardwareController@create');
$router->get('/hardware/edit', 'HardwareController@edit');
$router->post('/hardware/edit', 'HardwareController@edit');
$router->get('/hardware/delete', 'HardwareController@delete');

// SOFTWARE
$router->get('/software', 'SoftwareController@index');
$router->get('/software/create', 'SoftwareController@create');
$router->post('/software/create', 'SoftwareController@create');
$router->get('/software/edit', 'SoftwareController@edit');
$router->post('/software/edit', 'SoftwareController@edit');
$router->get('/software/delete', 'SoftwareController@delete');

// USER
$router->get('/users', 'UserController@index');
$router->get('/users/create', 'UserController@create');
$router->post('/users/create', 'UserController@create');
$router->get('/users/edit', 'UserController@edit');
$router->post('/users/edit', 'UserController@edit');
$router->get('/users/setActive', 'UserController@setActive');

// MITARBEITER
$router->get('/mitarbeiter', 'MitarbeiterController@index');
$router->get('/mitarbeiter/create', 'MitarbeiterController@create');
$router->post('/mitarbeiter/create', 'MitarbeiterController@create');
$router->get('/mitarbeiter/edit', 'MitarbeiterController@edit');
$router->post('/mitarbeiter/edit', 'MitarbeiterController@edit');
$router->post('/mitarbeiter/delete', 'MitarbeiterController@delete');

// WARENKORB (NEU!)
$router->get('/warenkorb', 'WarenkorbController@index');
$router->get('/warenkorb/hardware', 'WarenkorbController@hardware');
$router->get('/warenkorb/software', 'WarenkorbController@software');
$router->post('/warenkorb/addHardware', 'WarenkorbController@addHardware');
$router->post('/warenkorb/addSoftware', 'WarenkorbController@addSoftware');
$router->post('/warenkorb/removeHardware', 'WarenkorbController@removeHardware');
$router->post('/warenkorb/removeSoftware', 'WarenkorbController@removeSoftware');
$router->post('/warenkorb/abschliessen', 'WarenkorbController@abschliessen');

$router->dispatch();
