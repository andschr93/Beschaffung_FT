<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * BASIS-CONTROLLER-KLASSE
 * ═══════════════════════════════════════════════════════════════
 * 
 * Diese Klasse ist die "Eltern-Klasse" für alle Controller.
 * Alle anderen Controller (Hardware, Software, etc.) erben von ihr.
 * 
 * WICHTIGE FUNKTIONEN:
 * - render() → View anzeigen
 * - getCsrfToken() → CSRF-Token holen
 * - validateCsrf() → CSRF-Token prüfen (Sicherheit!)
 * - redirect() → Weiterleitung zu anderer Seite
 * 
 * ═══════════════════════════════════════════════════════════════
 */

class Controller {
    
    /**
     * VIEW RENDERN (= Anzeigen)
     * 
     * Diese Methode zeigt eine View-Datei im Layout an.
     * Sie ist die zentrale Methode zum Anzeigen von Seiten!
     * 
     * WIE FUNKTIONIERT DAS?
     * 1. View-Datei wird geladen (z.B. mitarbeiter/index.php)
     * 2. Ausgabe wird in $content gespeichert (Output Buffering)
     * 3. Layout-Datei (_layout.php) wird geladen
     * 4. Layout zeigt $content an
     * 
     * BEISPIEL:
     * $this->render('mitarbeiter/index', ['list' => $mitarbeiter]);
     * → Lädt: app/views/mitarbeiter/index.php
     * → Daten stehen in $data['list'] zur Verfügung
     * 
     * @param string $view Pfad zur View (ohne .php)
     * @param array $data Daten für die View (als Array)
     */
    protected function render($view, $data = []) {
        // Output Buffering starten (= Ausgabe zwischenspeichern)
        ob_start();
        
        // View-Datei laden (z.B. app/views/mitarbeiter/index.php)
        // Die View hat Zugriff auf $data
        include __DIR__ . '/../views/' . $view . '.php';
        
        // Ausgabe in Variable $content speichern
        $content = ob_get_clean();
        
        // Layout-Datei laden (zeigt Header, Sidebar, $content, Footer)
        include __DIR__ . '/../views/_layout.php';
    }
    
    /**
     * CSRF-TOKEN ABRUFEN
     * 
     * CSRF = Cross-Site Request Forgery (Angriffs-Art)
     * Token schützt davor, dass fremde Webseiten Aktionen
     * in Ihrem Namen ausführen.
     * 
     * Das Token wird in jedem Formular als Hidden-Field eingefügt.
     * Beim Absenden wird geprüft ob es stimmt.
     * 
     * @return string Das CSRF-Token aus der Session
     */
    protected function getCsrfToken() {
        return $_SESSION['csrf_token'] ?? '';
    }
    
    /**
     * CSRF-TOKEN VALIDIEREN (PRÜFEN)
     * 
     * Diese Methode wird in JEDEM Controller bei POST aufgerufen!
     * Sie verhindert CSRF-Angriffe.
     * 
     * ABLAUF:
     * 1. Token aus Formular holen ($_POST['csrf_token'])
     * 2. Mit Token aus Session vergleichen
     * 3. Stimmt nicht? → Abbruch mit Fehler 403
     * 4. Stimmt? → Weitermachen
     * 
     * BEISPIEL IM CONTROLLER:
     * if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     *     $this->validateCsrf();  ← Hier wird geprüft!
     *     // ... rest vom Code
     * }
     */
    protected function validateCsrf() {
        // Token aus Formular holen
        $postToken = $_POST['csrf_token'] ?? null;
        
        // Token aus Session holen
        $sessionToken = $_SESSION['csrf_token'] ?? null;
        
        // Vergleichen
        if (!$postToken || $postToken !== $sessionToken) {
            // Nicht identisch → ANGRIFF oder abgelaufene Session!
            http_response_code(403);  // 403 = Forbidden (Verboten)
            die('CSRF-Validierung fehlgeschlagen. Bitte versuchen Sie es erneut.');
        }
        // Alles ok → weitermachen
    }
    
    /**
     * REDIRECT (WEITERLEITUNG) HELPER
     * 
     * Leitet den User zu einer anderen Seite weiter.
     * Praktisch nach dem Speichern von Daten.
     * 
     * BEISPIEL:
     * $this->redirect('/mitarbeiter');
     * → User wird zu /mitarbeiter weitergeleitet
     * 
     * WICHTIG: exit() stoppt das Script komplett!
     * Sonst würde der Code nach dem redirect() weiterlaufen.
     * 
     * @param string $path Ziel-URL (z.B. '/mitarbeiter')
     */
    protected function redirect($path) {
        // HTTP-Header senden: "Gehe zu dieser URL"
        header("Location: " . BASE_URL . $path);
        
        // Script beenden (nichts mehr ausführen!)
        exit();
    }
}

// ═══════════════════════════════════════════════════════════════
// ENDE DER BASIS-CONTROLLER-KLASSE
// 
// WICHTIG ZU VERSTEHEN:
// - Alle anderen Controller erben von dieser Klasse
// - "extends Controller" = "erbt alle Methoden"
// - Deshalb können alle Controller render(), validateCsrf(), etc. nutzen
// - Das ist OBJEKTORIENTIERTE PROGRAMMIERUNG (OOP)!
// ═══════════════════════════════════════════════════════════════
