<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * AUTH-CONTROLLER - Authentifizierung (Login/Logout)
 * ═══════════════════════════════════════════════════════════════
 * 
 * Dieser Controller ist verantwortlich für:
 * - Login-Seite anzeigen
 * - Login verarbeiten (Benutzername + Passwort prüfen)
 * - Logout durchführen (Session beenden)
 * - Zugriffskontrolle (requireAuth, requireRole)
 * 
 * SICHERHEITSFEATURES:
 * ✓ CSRF-Schutz (Token-Validierung)
 * ✓ Rate Limiting (max 5 Fehlversuche)
 * ✓ Session Regeneration (gegen Session Fixation)
 * ✓ Input-Validierung (E-Mail, Passwort)
 * 
 * ═══════════════════════════════════════════════════════════════
 */

// Benötigte Klassen laden
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/models/AuthModel.php';

class AuthController extends Controller
{
    /**
     * LOGIN-SEITE ANZEIGEN
     * 
     * Diese Methode zeigt das Login-Formular an.
     * Sie wird aufgerufen wenn User /login aufruft (GET-Request).
     * 
     * BESONDERHEIT:
     * Die Login-Seite wird OHNE Layout angezeigt!
     * (Kein Header, keine Sidebar, nur das Formular)
     */
    public function index()
    {
        // Fehler-Variable initialisieren (leer beim ersten Aufruf)
        $error = '';
        
        // CSRF-Token aus Session holen (für Formular-Schutz)
        $csrf_token = $this->getCsrfToken();
        
        // Variablen für die View vorbereiten
        // compact() erstellt Array: ['error' => '', 'csrf_token' => '...']
        $data = compact('error', 'csrf_token');
        
        // Login-View direkt laden (OHNE _layout.php!)
        include BASE_PATH . '/app/views/auth/login.php';
    }

    /**
     * LOGIN VERARBEITEN (FORMULAR-ABSENDUNG)
     * 
     * Diese Methode wird aufgerufen wenn das Login-Formular abgeschickt wird.
     * Sie ist das Herzstück der Authentifizierung!
     * 
     * ABLAUF:
     * 1. CSRF-Token prüfen (Sicherheit)
     * 2. Rate Limiting prüfen (zu viele Fehlversuche?)
     * 3. Eingaben validieren (E-Mail, Passwort)
     * 4. Datenbank prüfen (User existiert? Passwort korrekt?)
     * 5. Session anlegen + weiterleiten ODER Fehler zeigen
     */
    public function login()
    {
        // Variablen initialisieren
        $error = '';
        $csrf_token = $this->getCsrfToken();

        // ═══ SCHRITT 1: Ist es ein POST-Request? ═══
        // POST = Formular wurde abgeschickt
        // GET = Seite wird nur angezeigt
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // ═══ SCHRITT 2: CSRF-VALIDIERUNG ═══
            // Prüfen ob Token aus Formular mit Token aus Session übereinstimmt
            // Das verhindert CSRF-Angriffe (Cross-Site Request Forgery)
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $error = "Ungültige Anfrage. Bitte versuchen Sie es erneut.";
                $data = compact('error', 'csrf_token');
                include BASE_PATH . '/app/views/auth/login.php';
                return;  // Abbruch! Nicht weitermachen.
            }
            
            // ═══ SCHRITT 3: RATE LIMITING PRÜFEN ═══
            // Verhindert Brute-Force-Angriffe (massenhafte Login-Versuche)
            // Nach 5 Fehlversuchen → 15 Minuten Sperre
            if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 5) {
                // Wann läuft die Sperre ab?
                $lockoutTime = $_SESSION['lockout_time'] ?? 0;
                
                // Ist die Sperre noch aktiv?
                if (time() < $lockoutTime) {
                    // JA → Fehler anzeigen
                    $remainingMinutes = ceil(($lockoutTime - time()) / 60);
                    $error = "Zu viele Fehlversuche. Bitte warten Sie $remainingMinutes Minute(n).";
                    $data = compact('error', 'csrf_token');
                    include BASE_PATH . '/app/views/auth/login.php';
                    return;  // Abbruch!
                } else {
                    // NEIN → Sperre ist abgelaufen, zurücksetzen
                    unset($_SESSION['login_attempts']);
                    unset($_SESSION['lockout_time']);
                }
            }
            
            // ═══ SCHRITT 4: INPUT-VALIDIERUNG ═══
            // Eingaben vom User NIEMALS direkt vertrauen!
            
            // E-Mail validieren mit PHP-Filter
            // filter_var() prüft ob es eine gültige E-Mail-Adresse ist
            // ?? '' = Falls $_POST['email'] nicht existiert → leerer String
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
            
            // Passwort holen (ohne Validierung, da alle Zeichen erlaubt)
            $password = $_POST['password'] ?? '';
            
            // E-Mail gültig?
            if (!$email) {
                $error = "Bitte geben Sie eine gültige E-Mail-Adresse ein.";
                $data = compact('error', 'csrf_token');
                include BASE_PATH . '/app/views/auth/login.php';
                return;  // Abbruch!
            }
            
            // Passwort vorhanden?
            if (empty($password)) {
                $error = "Bitte geben Sie ein Passwort ein.";
                $data = compact('error', 'csrf_token');
                include BASE_PATH . '/app/views/auth/login.php';
                return;  // Abbruch!
            }
            
            // ═══ SCHRITT 5: USER IN DATENBANK PRÜFEN ═══
            // AuthModel::verifyUser() prüft:
            // - Existiert ein User mit dieser E-Mail?
            // - Ist das Passwort korrekt?
            // Rückgabe: User-Array bei Erfolg, null bei Fehler
            $user = AuthModel::verifyUser($email, $password);

            // ═══ SCHRITT 6: LOGIN ERFOLGREICH? ═══
            if ($user) {
                // ✅ JA → USER IST AUTHENTIFIZIERT!
                
                // Fehlversuche zurücksetzen
                unset($_SESSION['login_attempts']);
                unset($_SESSION['lockout_time']);
                
                // ═══ WICHTIG: SESSION REGENERIEREN! ═══
                // Erzeugt neue Session-ID → verhindert Session Fixation Angriff
                // true = alte Session-Datei löschen
                session_regenerate_id(true);
                
                // User-Daten in Session speichern
                // Diese Daten stehen auf ALLEN Seiten zur Verfügung!
                $_SESSION['user'] = [
                    'id'       => $user['id'],
                    'rolle_id' => $user['rolle_id'],
                    'vorname'  => $user['vorname'],
                    'nachname' => $user['nachname'],
                ];
                
                // ═══ WEITERLEITUNG BASIEREND AUF ROLLE ═══
                // Jede Rolle bekommt ein eigenes Dashboard
                // Rolle 1 = Admin, Rolle 2 = IT, Rolle 3 = User Management, etc.
                switch ($user['rolle_id']) {
                    case 1: $target = '/admin'; break;           // Admin-Dashboard
                    case 2: $target = '/it-dashboard'; break;    // IT-Dashboard
                    case 3: $target = '/users'; break;           // User-Management
                    case 4: $target = '/mitarbeiter'; break;     // Personal/Vorzimmer
                    case 5: $target = '/it-dashboard'; break;    // IT (alternative Rolle)
                    default: $target = '/'; break;               // Home (Fallback)
                }
                
                // Weiterleitung durchführen
                $this->redirect($target);
                
            } else {
                // ❌ NEIN → LOGIN FEHLGESCHLAGEN!
                
                // Fehlversuche hochzählen
                // ?? 0 = Falls noch nicht gesetzt, starte bei 0
                $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
                
                // 5 Fehlversuche erreicht?
                if ($_SESSION['login_attempts'] >= 5) {
                    // JA → Account sperren für 15 Minuten
                    $_SESSION['lockout_time'] = time() + (15 * 60);  // 15 Min = 900 Sek
                    $error = "Zu viele Fehlversuche. Ihr Zugang wurde für 15 Minuten gesperrt.";
                } else {
                    // NEIN → Noch Versuche übrig
                    $remaining = 5 - $_SESSION['login_attempts'];
                    $error = "Login fehlgeschlagen: Ungültige E-Mail oder Passwort! (Noch $remaining Versuche)";
                }
                
                // Login-Seite mit Fehler anzeigen
                $data = compact('error', 'csrf_token');
                include BASE_PATH . '/app/views/auth/login.php';
            }
        } else {
            // ═══ KEIN POST-REQUEST → SEITE NUR ANZEIGEN ═══
            // Wird ausgeführt wenn User direkt /login aufruft
            $data = compact('error', 'csrf_token');
            include BASE_PATH . '/app/views/auth/login.php';
        }
    }

    /**
     * LOGOUT (ABMELDEN)
     * 
     * Diese Methode meldet den User ab und löscht die Session.
     * Wird aufgerufen wenn User auf "Abmelden" klickt.
     * 
     * ABLAUF:
     * 1. Alle Session-Variablen löschen
     * 2. Session komplett zerstören
     * 3. Weiterleitung zu Login-Seite
     */
    public function logout()
    {
        // Alle Session-Variablen löschen (z.B. $_SESSION['user'])
        session_unset();
        
        // Session komplett zerstören (Session-Datei wird gelöscht)
        session_destroy();
        
        // Weiterleitung zur Login-Seite
        $this->redirect('/login');
    }

    /**
     * ═══════════════════════════════════════════════════════════
     * ZUGRIFFSKONTROLLE - MIDDLEWARE-METHODEN
     * ═══════════════════════════════════════════════════════════
     * 
     * Diese Methoden werden in ANDEREN Controllern aufgerufen,
     * um Zugriff zu beschränken.
     * 
     * BEISPIEL:
     * class AdminController {
     *     public function index() {
     *         AuthController::requireRole([1]); // Nur Admin!
     *         // ... rest vom Code
     *     }
     * }
     */

    /**
     * ROLLENPRÜFUNG (Nur bestimmte Rollen dürfen zugreifen)
     * 
     * Diese Methode prüft ob der eingeloggte User eine der
     * erlaubten Rollen hat.
     * 
     * VERWENDUNG:
     * AuthController::requireRole([1, 2]); // Nur Admin + IT
     * 
     * ABLAUF:
     * 1. Ist User eingeloggt?
     * 2. Hat User eine der erlaubten Rollen?
     * 3. NEIN → 403 Fehler + Redirect zu Login
     * 4. JA → Weitermachen
     * 
     * @param array $allowedRoles Array mit erlaubten Rollen-IDs (z.B. [1, 2])
     */
    public static function requireRole(array $allowedRoles)
    {
        // User eingeloggt UND Rolle erlaubt?
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rolle_id'], $allowedRoles)) {
            // NEIN → Zugriff verweigern!
            http_response_code(403);  // 403 = Forbidden (Verboten)
            header("Location: " . BASE_URL . "/login");
            exit();  // Script beenden!
        }
        // JA → Weitermachen (return passiert automatisch)
    }
    
    /**
     * AUTHENTIFIZIERUNGSPRÜFUNG (Ist User überhaupt eingeloggt?)
     * 
     * Diese Methode prüft NUR ob User eingeloggt ist.
     * Die Rolle ist egal!
     * 
     * VERWENDUNG:
     * AuthController::requireAuth();
     * 
     * ABLAUF:
     * 1. Existiert $_SESSION['user']?
     * 2. NEIN → 401 Fehler + Redirect zu Login
     * 3. JA → Weitermachen
     */
    public static function requireAuth()
    {
        // User eingeloggt?
        if (!isset($_SESSION['user'])) {
            // NEIN → Bitte erst einloggen!
            http_response_code(401);  // 401 = Unauthorized (Nicht autorisiert)
            header("Location: " . BASE_URL . "/login");
            exit();  // Script beenden!
        }
        // JA → Weitermachen
    }
}

// ═══════════════════════════════════════════════════════════════
// ENDE DES AUTH-CONTROLLERS
// 
// WICHTIGE KONZEPTE FÜR IHK-PRÜFUNG:
// 
// 1. AUTHENTIFIZIERUNG vs. AUTORISIERUNG:
//    - Authentifizierung = Wer bist du? (Login)
//    - Autorisierung = Was darfst du? (Rollen)
// 
// 2. SICHERHEITSFEATURES:
//    - CSRF-Schutz (Token)
//    - Rate Limiting (Brute-Force-Schutz)
//    - Session Regeneration (Session Fixation Schutz)
//    - Input-Validierung (E-Mail, Passwort)
// 
// 3. SESSION:
//    - Speichert User-Daten über mehrere Seiten hinweg
//    - Liegt auf dem SERVER (nicht im Browser!)
//    - Browser bekommt nur Session-ID als Cookie
// 
// ═══════════════════════════════════════════════════════════════
