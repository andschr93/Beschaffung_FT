<?php
/**
 * Zentrale Konfigurationsdatei
 * Für lokale Entwicklung und IHK-Prüfungsprojekt
 */

// Fehlerbehandlung basierend auf Umgebung
$isProduction = getenv('APP_ENV') === 'production';

if (!$isProduction) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
}

// Base URL - automatisch ermitteln oder aus ENV
$baseUrl = getenv('BASE_URL');
if (!$baseUrl) {
    // Automatische Ermittlung für lokale Entwicklung
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
    $baseUrl = $protocol . '://' . $host . $scriptName . '/public';
}
define('BASE_URL', rtrim($baseUrl, '/'));

// Session-Konfiguration
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// CSRF Token generieren falls nicht vorhanden
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Debug-Modus
define('APP_DEBUG', getenv('APP_DEBUG') === 'true' || !$isProduction);

