<?php

class Database {
    private $conn;
    private $lastError;

    public function __construct($envPath = __DIR__ . '/../../.env') {

        $config = $this->loadEnv($envPath);

        // Verbindung als Connection String zusammenbauen
        if (!empty($config['DB_TRUSTED_CONNECTION'])) {
            // Windows Auth
            $connectionString = "Server=" . $config['DB_HOST'] . ";"
                              . "Database=" . $config['DB_DATABASE'] . ";"
                              . "Trusted_Connection=Yes;";
            $connectionInfo = [ "CharacterSet" => "UTF-8" ];
        } else {
            // SQL Login
            $connectionString = $config['DB_HOST'];
            $connectionInfo = [
                "Database" => $config['DB_DATABASE'],
                "UID"      => $config['DB_USER'],
                "PWD"      => $config['DB_PASSWORD'],
                "CharacterSet" => "UTF-8"
            ];
        }

        // Verbindung herstellen
        $this->conn = sqlsrv_connect($connectionString, $connectionInfo);

        if (!$this->conn) {
            $this->lastError = sqlsrv_errors();
            throw new Exception("Connection failed: " . print_r($this->lastError, true));
        }
    }

    private function loadEnv($path) {
        if (!file_exists($path)) {
            throw new Exception(".env-Datei nicht gefunden: $path");
        }

        $env = [];
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with(trim($line), '#')) continue;
            list($key, $value) = array_map('trim', explode('=', $line, 2));
            $env[$key] = $value;
        }
        return $env;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function getLastError() {
        return $this->lastError;
    }

    public function query($sql, $params = []) {
        if (!$this->conn) return false;

        foreach ($params as $k => $v) {
            if (is_string($v) && strlen($v) === 32) { // 32 bytes = SHA256 binary
                $params[$k] = [
                    $v,
                    SQLSRV_PARAM_IN,
                    SQLSRV_PHPTYPE_STREAM('binary'),
                    SQLSRV_SQLTYPE_BINARY(32)
                ];
            }
        }
        $stmt = sqlsrv_prepare($this->conn, $sql, $params);
        if (!$stmt) {
            file_put_contents('debug_pw.txt', "sql error: ".print_r(sqlsrv_errors(), true)."\n", FILE_APPEND);
            $this->lastError = sqlsrv_errors();
            return false;
        }
        if (!sqlsrv_execute($stmt)) {
            file_put_contents('debug_pw.txt', "sql execute error: ".print_r(sqlsrv_errors(), true)."\n", FILE_APPEND);
            $this->lastError = sqlsrv_errors();
            return false;
        }
        return $stmt;
    }

    public function fetchAll($stmt) {
        $rows = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function __destruct() {
        if ($this->conn) sqlsrv_close($this->conn);
    }
}
