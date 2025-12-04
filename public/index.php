<?php

define('BASE_PATH', dirname(__DIR__));

// Router laden
require_once BASE_PATH . '/app/core/Router.php';

// Database nicht zwingend hier notwendig, wird im Controller geladen
// require_once BASE_PATH . '/app/config/database.php';

// Router starten
$router = new Router();

// ROUTEN DEFINIEREN (WICHTIG!)
$router->get('/', 'HomeController@index');

$router->get('/login', 'AuthController@index');   // Login-Seite anzeigen
$router->post('/login', 'AuthController@login');  // Login-Verarbeitung
$router->get('/logout', 'AuthController@logout'); // Logout
$router->get('/admin', 'AdminController@index');

// Später:
/// $router->get('/mitarbeiter/anlegen', 'MitarbeiterController@create');
//  $router->post('/mitarbeiter/anlegen', 'MitarbeiterController@store');


/// ROUTING AUSFÜHREN
$router->dispatch();
