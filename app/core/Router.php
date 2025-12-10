<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * ROUTER-KLASSE - Das Herzstück der URL-Verarbeitung
 * ═══════════════════════════════════════════════════════════════
 * 
 * Der Router ist wie ein "Verteiler" in einem Postamt:
 * - Er empfängt alle Anfragen (URLs)
 * - Er schaut nach: Welcher Controller ist dafür zuständig?
 * - Er ruft die richtige Controller-Methode auf
 * 
 * BEISPIEL:
 * URL: /mitarbeiter → MitarbeiterController → Methode: index()
 * URL: /login → AuthController → Methode: index()
 * 
 * ═══════════════════════════════════════════════════════════════
 */

class Router {
    /**
     * Array zum Speichern aller registrierten Routen
     * 
     * Struktur:
     * $routes = [
     *   'GET' => [
     *     '/' => 'HomeController@index',
     *     '/hardware' => 'HardwareController@index'
     *   ],
     *   'POST' => [
     *     '/login' => 'AuthController@login'
     *   ]
     * ]
     */
    private $routes = [
        'GET' => [],   // Für normale Seitenaufrufe
        'POST' => []   // Für Formular-Absendungen
    ];

    /**
     * GET-Route registrieren
     * 
     * Wird aufgerufen mit: $router->get('/hardware', 'HardwareController@index');
     * Bedeutet: Wenn jemand /hardware aufruft → HardwareController->index()
     * 
     * @param string $route URL-Pfad (z.B. '/hardware')
     * @param string $action Controller@Methode (z.B. 'HardwareController@index')
     */
    public function get($route, $action) {
        $this->routes['GET'][$route] = $action;
    }

    /**
     * POST-Route registrieren
     * 
     * Wird für Formulare verwendet (z.B. Login, Speichern, Löschen)
     * 
     * @param string $route URL-Pfad
     * @param string $action Controller@Methode
     */
    public function post($route, $action) {
        $this->routes['POST'][$route] = $action;
    }

    /**
     * HAUPTMETHODE: Verarbeitet die aktuelle Anfrage (Request)
     * 
     * Diese Methode wird in index.php aufgerufen und ist der Einstiegspunkt
     * für ALLE Seiten-Aufrufe!
     * 
     * ABLAUF:
     * 1. URL analysieren (z.B. /mitarbeiter)
     * 2. Passende Route suchen
     * 3. Controller-Datei laden
     * 4. Controller-Objekt erstellen
     * 5. Methode aufrufen
     */
    public function dispatch() {
        // ═══ SCHRITT 1: REQUEST-METHOD ermitteln ═══
        // GET = normale Seitenaufrufe, POST = Formular-Absendungen
        $method = $_SERVER['REQUEST_METHOD'];

        // ═══ SCHRITT 2: URL aus der Anfrage extrahieren ═══
        // z.B. http://localhost/Beschaffung_FT/public/mitarbeiter?id=5
        //      → parse_url liefert nur: /Beschaffung_FT/public/mitarbeiter
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // ═══ SCHRITT 3: Projektordner dynamisch entfernen ═══
        // Aus /Beschaffung_FT/public/mitarbeiter wird /mitarbeiter
        // Das funktioniert auf jedem Server (localhost, produktiv, etc.)
        $scriptName = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '' && str_starts_with($uri, $scriptName)) {
            $uri = substr($uri, strlen($scriptName));
        }

        // Leere URL oder "/" wird immer zu "/"
        if ($uri === '' || $uri === '/') {
            $uri = '/';
        }

        // ═══ SCHRITT 4: Gibt es eine Route für diese URL? ═══
        if (!isset($this->routes[$method][$uri])) {
            // NEIN → 404-Fehler ausgeben
            http_response_code(404);
            echo "<h2>404 - Route '$uri' nicht gefunden</h2>";
            return;
        }

        // ═══ SCHRITT 5: Controller und Methode extrahieren ═══
        // Aus "HardwareController@index" wird:
        // $controllerName = "HardwareController"
        // $action = "index"
        list($controllerName, $action) = explode('@', $this->routes[$method][$uri]);

        // ═══ SCHRITT 6: Controller-Datei laden ═══
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

        // Prüfen: Existiert die Datei überhaupt?
        if (!file_exists($controllerFile)) {
            http_response_code(500);
            if (APP_DEBUG) {
                echo "Controller-Datei '$controllerFile' wurde nicht gefunden!";
            } else {
                echo "Ein Fehler ist aufgetreten.";
            }
            return;
        }

        // Datei laden (require_once = nur 1x laden, auch bei mehreren Aufrufen)
        require_once $controllerFile;

        // ═══ SCHRITT 7: Prüfen ob Controller-Klasse existiert ═══
        if (!class_exists($controllerName)) {
            http_response_code(500);
            if (APP_DEBUG) {
                echo "Controller-Klasse '$controllerName' existiert nicht!";
            } else {
                echo "Ein Fehler ist aufgetreten.";
            }
            return;
        }

        // ═══ SCHRITT 8: Controller-Objekt erstellen ═══
        // new HardwareController() → Konstruktor wird aufgerufen (falls vorhanden)
        $controller = new $controllerName();

        // ═══ SCHRITT 9: Prüfen ob Methode existiert ═══
        if (!method_exists($controller, $action)) {
            http_response_code(500);
            if (APP_DEBUG) {
                echo "Methode '$action' existiert im Controller '$controllerName' nicht!";
            } else {
                echo "Ein Fehler ist aufgetreten.";
            }
            return;
        }

        // ═══ SCHRITT 10: METHODE AUFRUFEN! ═══
        // z.B. $controller->index()
        // Ab hier übernimmt der Controller die Kontrolle
        $controller->$action();
    }
}

// ═══════════════════════════════════════════════════════════════
// ENDE DER ROUTER-KLASSE
// 
// ZUSAMMENFASSUNG:
// Der Router ist der "Dirigent" des Systems:
// - Er empfängt ALLE Anfragen
// - Er findet den richtigen Controller
// - Er ruft die richtige Methode auf
// - Das ist das MVC-Pattern in Aktion!
// ═══════════════════════════════════════════════════════════════
