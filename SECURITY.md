# Security-Dokumentation

## 🔒 Implementierte Sicherheitsmaßnahmen

### 1. SQL-Injection-Schutz

**Implementierung:**
- Alle Datenbankabfragen verwenden Prepared Statements über `sqlsrv_prepare()`
- Keine direkte String-Konkatenation in SQL-Queries
- Parameter-Binding für alle User-Inputs

**Beispiel:**
```php
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $db->query($sql, [$email]);
```

### 2. XSS-Schutz (Cross-Site-Scripting)

**Implementierung:**
- Alle Ausgaben werden mit `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')` escaped
- Keine direkten `echo $_POST` oder ähnliches
- Views verwenden konsequent Escaping

**Beispiel:**
```php
<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>
```

### 3. CSRF-Schutz (Cross-Site-Request-Forgery)

**Implementierung:**
- CSRF-Token wird in Session generiert (`config/config.php`)
- Jedes Formular enthält CSRF-Token als Hidden-Field
- Controller validieren Token vor Verarbeitung

**Beispiel:**
```php
// In Controller:
$this->validateCsrf();

// In View:
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
```

### 4. Passwort-Sicherheit

**Implementierung:**
- `password_hash()` mit PASSWORD_DEFAULT (aktuell BCrypt)
- `password_verify()` für Login-Prüfung
- Mindestlänge: 8 Zeichen
- Timing-Attack-Schutz durch `password_verify()`

**Beispiel:**
```php
// Hash erstellen:
$hash = password_hash($password, PASSWORD_DEFAULT);

// Vergleichen:
if (password_verify($password, $storedHash)) {
    // Login erfolgreich
}
```

### 5. Rate-Limiting

**Implementierung:**
- Login-Versuche werden in Session gezählt
- Nach 5 Fehlversuchen: 15 Minuten Sperre
- Counter wird bei erfolgreichem Login zurückgesetzt

**Code:**
```php
if ($_SESSION['login_attempts'] >= 5) {
    if (time() < $_SESSION['lockout_time']) {
        // Gesperrt
    }
}
```

### 6. Input-Validierung

**Implementierung:**
- Email: `filter_var($email, FILTER_VALIDATE_EMAIL)`
- IDs: `filter_var($id, FILTER_VALIDATE_INT)`
- Strings: `trim()` + Längenprüfung
- Whitelist-Validierung wo möglich

**Beispiel:**
```php
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if (!$email) {
    $error = "Ungültige E-Mail-Adresse!";
}
```

### 7. Session-Sicherheit

**Implementierung:**
```php
ini_set('session.cookie_httponly', 1);  // Kein JS-Zugriff
ini_set('session.cookie_samesite', 'Lax');  // CSRF-Schutz
ini_set('session.use_strict_mode', 1);  // Keine vorgegebenen Session-IDs
session_regenerate_id(true);  // Nach Login neue Session-ID
```

### 8. HTTP-Only DELETE/POST

**Implementierung:**
- DELETE-Aktionen nur über POST-Requests
- Keine kritischen Aktionen über GET
- Method-Check in Controllern

**Beispiel:**
```php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Methode nicht erlaubt');
}
```

### 9. Error-Handling

**Implementierung:**
- Zentrale Exception-Handler
- Produktions-Modus: Generische Fehlermeldungen
- Debug-Modus: Detaillierte Fehler
- Logging in `error_log()`

**Code:**
```php
set_exception_handler(function($e) {
    error_log($e->getMessage());
    if (APP_DEBUG) {
        // Details zeigen
    } else {
        echo "Ein Fehler ist aufgetreten.";
    }
});
```

### 10. Autorisierung

**Implementierung:**
- `AuthController::requireAuth()` für Login-Prüfung
- `AuthController::requireRole([...])` für Rollen-Prüfung
- Wird am Anfang jeder geschützten Methode aufgerufen

**Beispiel:**
```php
public function index() {
    AuthController::requireAuth();
    AuthController::requireRole([1, 3]); // Nur Admin + Vorzimmer
    // ...
}
```

## 🚨 Bekannte Einschränkungen

1. **Kein HTTPS** - Lokal entwickelt, in Produktion HTTPS verwenden!
2. **Session in Files** - In Produktion Redis/Memcached nutzen
3. **Kein 2FA** - Könnte als Erweiterung implementiert werden
4. **Kein Account-Lockout bei Brute-Force** - Nur temporär (15 Min.)

## 📋 Sicherheits-Checkliste für Deployment

- [ ] HTTPS aktivieren
- [ ] `APP_DEBUG=false` setzen
- [ ] `display_errors=0` in PHP
- [ ] `.env` aus Webroot entfernen
- [ ] Datenbank-User mit minimalen Rechten
- [ ] Regelmäßige Backups
- [ ] PHP + Dependencies aktuell halten
- [ ] Error-Logs monitoren
- [ ] Rate-Limiting auf Infrastruktur-Ebene (z.B. nginx)

## 🔍 Penetration-Testing

Folgende Tests wurden durchgeführt:

- ✅ SQL-Injection (versucht über alle Input-Felder)
- ✅ XSS (versucht über alle Text-Felder)
- ✅ CSRF (ohne Token abgelehnt)
- ✅ Directory-Traversal (keine sensiblen Dateien erreichbar)
- ✅ Brute-Force-Login (nach 5 Versuchen gesperrt)
- ✅ Session-Fixation (session_regenerate_id nach Login)

## 📞 Meldung von Sicherheitslücken

Bitte melden Sie Sicherheitslücken vertraulich an den Projektverantwortlichen.

---

**Letzte Sicherheits-Review:** Dezember 2024

