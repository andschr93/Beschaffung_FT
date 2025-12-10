<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * DATENBANK-VERBINDUNGS-KLASSE
 * ═══════════════════════════════════════════════════════════════
 * 
 * Diese Klasse kümmert sich um die Verbindung zur SQL Server Datenbank.
 * Sie liest die Zugangsdaten aus der .env Datei und stellt Prepared Statements
 * zur Verfügung, um SQL-Injection-Angriffe zu verhindern.
 * 
 * Verwendung:
 * $db = new Database();
 * $sql = "SELECT * FROM users WHERE email = ?";
 * $stmt = $db->query($sql, [$email]);
 * $users = $db->fetchAll($stmt);
 * 
 * ═══════════════════════════════════════════════════════════════
 */

class Database {
    // Variable für die Datenbankverbindung (private = nur in dieser Klasse nutzbar)
    private $conn;
    
    // Variable zum Speichern von Fehlermeldungen
    private $lastError;

    /**
     * KONSTRUKTOR - wird automatisch beim Erstellen eines Database-Objekts aufgerufen
     * 
     * Lädt die .env Datei und baut die Verbindung zum SQL Server auf.
     * Unterstützt sowohl Windows-Authentifizierung als auch SQL-Login.
     * 
     * @param string $envPath Pfad zur .env Datei (standardmäßig im Projekt-Root)
     * @throws Exception Wenn die Verbindung fehlschlägt
     */
    public function __construct($envPath = __DIR__ . '/../../.env') {
        
        // .env Datei laden (enthält DB-Zugangsdaten)
        $config = $this->loadEnv($envPath);

        // ═══ VERBINDUNGS-STRING ZUSAMMENBAUEN ═══
        
        // Prüfen: Verwenden wir Windows-Authentifizierung?
        if (!empty($config['DB_TRUSTED_CONNECTION'])) {
            // JA → Windows Auth (kein Username/Passwort nötig)
            $connectionString = "Server=" . $config['DB_HOST'] . ";"
                              . "Database=" . $config['DB_DATABASE'] . ";"
                              . "Trusted_Connection=Yes;";
            $connectionInfo = [ "CharacterSet" => "UTF-8" ];
        } else {
            // NEIN → SQL Server Login mit Username und Passwort
            $connectionString = $config['DB_HOST'];
            $connectionInfo = [
                "Database" => $config['DB_DATABASE'],
                "UID"      => $config['DB_USER'],
                "PWD"      => $config['DB_PASSWORD'],
                "CharacterSet" => "UTF-8"
            ];
        }

        // ═══ VERBINDUNG HERSTELLEN ═══
        $this->conn = sqlsrv_connect($connectionString, $connectionInfo);

        // Prüfen: Hat die Verbindung geklappt?
        if (!$this->conn) {
            // NEIN → Fehler speichern und Exception werfen (Programmabbruch)
            $this->lastError = sqlsrv_errors();
            throw new Exception("Datenbankverbindung fehlgeschlagen: " . print_r($this->lastError, true));
        }
        // JA → Verbindung steht, weiter im Code
    }

    /**
     * PRIVATE HILFSMETHODE: .env Datei laden und parsen
     * 
     * Liest die .env Datei Zeile für Zeile und extrahiert KEY=VALUE Paare.
     * Ignoriert Kommentare (Zeilen die mit # beginnen) und leere Zeilen.
     * 
     * @param string $path Pfad zur .env Datei
     * @return array Assoziatives Array mit KEY => VALUE Paaren
     * @throws Exception Wenn .env Datei nicht existiert
     */
    private function loadEnv($path) {
        // Prüfen: Existiert die .env Datei überhaupt?
        if (!file_exists($path)) {
            throw new Exception(".env-Datei nicht gefunden: $path");
        }

        // Leeres Array für die Konfiguration anlegen
        $env = [];
        
        // Datei Zeile für Zeile durchgehen
        // FILE_IGNORE_NEW_LINES = \n am Ende entfernen
        // FILE_SKIP_EMPTY_LINES = Leere Zeilen überspringen
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            // Leerzeichen am Anfang/Ende entfernen
            $line = trim($line);
            
            // Zeile leer oder Kommentar? → Überspringen
            if ($line === '' || str_starts_with($line, '#')) continue;
            
            // Prüfen: Enthält die Zeile ein "=" Zeichen?
            if (!str_contains($line, '=')) {
                // NEIN → Fehlerhafte Zeile, ins Log schreiben und überspringen
                error_log("Ungültige .env Zeile: $line");
                continue;
            }
            
            // Zeile am ersten "=" aufteilen (z.B. "DB_HOST=localhost")
            // explode($line, 2) = max. 2 Teile (falls VALUE auch "=" enthält)
            $parts = explode('=', $line, 2);
            $key = trim($parts[0]);           // z.B. "DB_HOST"
            $value = isset($parts[1]) ? trim($parts[1]) : '';  // z.B. "localhost"
            
            // Nur speichern wenn KEY nicht leer ist
            if ($key !== '') {
                $env[$key] = $value;
            }
        }
        
        // Fertiges Array mit allen Konfigurationswerten zurückgeben
        return $env;
    }

    /**
     * Gibt die aktuelle Datenbankverbindung zurück
     * 
     * @return resource|false Die SQL Server Verbindung oder false
     */
    public function getConnection() {
        return $this->conn;
    }

    /**
     * Gibt den letzten aufgetretenen Fehler zurück
     * 
     * Nützlich für Debugging: Wenn eine Query fehlschlägt, kann man hier
     * die genaue Fehlermeldung abrufen.
     * 
     * @return array|null Array mit Fehlerinformationen oder null
     */
    public function getLastError() {
        return $this->lastError;
    }

    /**
     * HAUPTMETHODE: SQL-Query mit Prepared Statements ausführen
     * 
     * Diese Methode schützt vor SQL-Injection-Angriffen!
     * Statt SQL direkt zu schreiben, verwenden wir Platzhalter (?)
     * und übergeben die Werte separat als Array.
     * 
     * BEISPIEL:
     * Unsicher:  $sql = "SELECT * FROM users WHERE email = '$email'";  ← GEFAHR!
     * Sicher:    $sql = "SELECT * FROM users WHERE email = ?";
     *            $stmt = $db->query($sql, [$email]);  ← SICHER!
     * 
     * @param string $sql SQL-Query mit Platzhaltern (?)
     * @param array $params Array mit Werten für die Platzhalter
     * @return resource|false Statement-Handle oder false bei Fehler
     */
    public function query($sql, $params = []) {
        // Prüfen: Ist überhaupt eine Verbindung da?
        if (!$this->conn) return false;

        // ═══ SCHRITT 1: PREPARED STATEMENT VORBEREITEN ═══
        // sqlsrv_prepare bindet die Parameter sicher ein (verhindert SQL-Injection!)
        $stmt = sqlsrv_prepare($this->conn, $sql, $params);
        
        // Hat das Vorbereiten geklappt?
        if (!$stmt) {
            // NEIN → Fehler speichern
            $this->lastError = sqlsrv_errors();
            
            // Im Debug-Modus: Fehler ins Log schreiben
            if (APP_DEBUG) {
                error_log("SQL Error: " . print_r($this->lastError, true));
            }
            return false;
        }
        
        // ═══ SCHRITT 2: STATEMENT AUSFÜHREN ═══
        if (!sqlsrv_execute($stmt)) {
            // Ausführung fehlgeschlagen → Fehler speichern
            $this->lastError = sqlsrv_errors();
            
            // Im Debug-Modus: Fehler ins Log schreiben
            if (APP_DEBUG) {
                error_log("SQL Execute Error: " . print_r($this->lastError, true));
            }
            return false;
        }
        
        // Alles geklappt! Statement-Handle zurückgeben
        return $stmt;
    }

    /**
     * Alle Ergebnisse einer Query als Array zurückgeben
     * 
     * Wandelt das SQL-Result-Set in ein PHP-Array um.
     * Jede Zeile wird ein Array-Element (assoziatives Array).
     * 
     * BEISPIEL:
     * $stmt = $db->query("SELECT * FROM users");
     * $users = $db->fetchAll($stmt);
     * // $users = [
     * //   0 => ['id' => 1, 'name' => 'Max', ...],
     * //   1 => ['id' => 2, 'name' => 'Anna', ...],
     * // ]
     * 
     * @param resource $stmt Statement-Handle von query()
     * @return array Array mit allen Ergebniszeilen
     */
    public function fetchAll($stmt) {
        // Leeres Array für die Ergebnisse anlegen
        $rows = [];
        
        // Solange es noch Zeilen gibt, hole sie nacheinander
        // SQLSRV_FETCH_ASSOC = Assoziatives Array (Spaltenname => Wert)
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[] = $row;  // Zeile ans Array anhängen
        }
        
        // Fertiges Array mit allen Zeilen zurückgeben
        return $rows;
    }

    /**
     * DESTRUKTOR - wird automatisch aufgerufen wenn das Objekt gelöscht wird
     * 
     * Schließt die Datenbankverbindung sauber, um Ressourcen freizugeben.
     * PHP ruft diese Methode automatisch auf (z.B. am Skript-Ende).
     */
    public function __destruct() {
        // Wenn eine Verbindung existiert, schließe sie
        if ($this->conn) sqlsrv_close($this->conn);
    }
}
// ═══════════════════════════════════════════════════════════════
// ENDE DER DATABASE-KLASSE
// ═══════════════════════════════════════════════════════════════
