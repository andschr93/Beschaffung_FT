<?php

class Router {
    private $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function get($route, $action) {
        $this->routes['GET'][$route] = $action;
    }

    public function post($route, $action) {
        $this->routes['POST'][$route] = $action;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];

        // URL auslesen
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Deinen Projektordner entfernen -> SEHR WICHTIG!
        $base = '/Beschaffung_FT/public';
        $uri = str_replace($base, '', $uri);

        // Leere URL -> Home
        if ($uri === '' || $uri === '/') {
            $uri = '/';
        }

        // Prüfen, ob Route existiert
        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo "<h2>404 - Route '$uri' nicht gefunden</h2>";
            return;
        }

        // Controller und Action extrahieren
        list($controllerName, $action) = explode('@', $this->routes[$method][$uri]);

        // Controller-Datei suchen
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            echo "Controller-Datei '$controllerFile' wurde nicht gefunden!";
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            echo "Controller-Klasse '$controllerName' existiert nicht!";
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $action)) {
            echo "Methode '$action' existiert im Controller '$controllerName' nicht!";
            return;
        }

        // Action ausführen
        $controller->$action();
    }
}
