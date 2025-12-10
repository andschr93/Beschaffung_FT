# Beschaffungs- und Onboarding-System

IHK Prüfungsprojekt - Webbasiertes System zur Verwaltung von Hardware, Software, Benutzern und Mitarbeiter-Onboarding.

## 🚀 Features

- ✅ Benutzer-Authentifizierung mit Rollen-System
- ✅ Hardware-Stammdaten-Verwaltung
- ✅ Software-Stammdaten-Verwaltung
- ✅ Mitarbeiter-Onboarding-Verwaltung
- ✅ Benutzerverwaltung (Admin/Vorzimmer)
- ✅ CSRF-Protection
- ✅ XSS-Schutz
- ✅ SQL-Injection-Schutz (Prepared Statements)
- ✅ Rate-Limiting beim Login
- ✅ Sichere Passwort-Hashes (password_hash)

## 📋 Voraussetzungen

- PHP 8.0 oder höher
- Microsoft SQL Server 2019+
- SQLSRV PHP Extension
- XAMPP oder vergleichbare Entwicklungsumgebung

## ⚙️ Installation

1. **Repository in XAMPP htdocs platzieren:**
   ```
   C:\xampp\htdocs\Beschaffung_FT\
   ```

2. **.env Datei erstellen:**
   - Kopieren Sie `.env.example` nach `.env`
   - Passen Sie die Datenbankverbindung an:
   ```
   DB_HOST=SERVERNAME\INSTANZ
   DB_DATABASE=Beschaffung_FT
   DB_TRUSTED_CONNECTION=true
   APP_ENV=development
   APP_DEBUG=true
   ```

3. **Datenbank einrichten:**
   - Führen Sie das SQL-Setup-Skript aus (falls vorhanden)
   - Stellen Sie sicher, dass alle Tabellen erstellt sind

4. **Web-Server starten:**
   - XAMPP Apache starten
   - Browser öffnen: `http://localhost/Beschaffung_FT/public/`

## 🔐 Sicherheits-Features

### CSRF-Protection
Alle Formulare sind mit CSRF-Tokens geschützt. Diese werden automatisch in der Session generiert.

### XSS-Schutz
Alle Ausgaben werden mit `htmlspecialchars()` escaped.

### SQL-Injection-Schutz
Alle Datenbankabfragen verwenden Prepared Statements.

### Passwort-Sicherheit
- Passwörter werden mit `password_hash()` (BCrypt/Argon2) gehasht
- Mindestlänge: 8 Zeichen
- Legacy SHA256-Hashes werden noch unterstützt (Migration)

### Rate-Limiting
- Nach 5 fehlgeschlagenen Login-Versuchen wird der Zugang für 15 Minuten gesperrt

### Input-Validierung
- Email-Adressen werden mit `filter_var()` validiert
- IDs werden mit `FILTER_VALIDATE_INT` geprüft
- Strings werden mit `trim()` bereinigt
- Maximale Längen werden serverseitig geprüft

## 📁 Projektstruktur

```
Beschaffung_FT/
├── app/
│   ├── controllers/     # Controller-Klassen
│   ├── core/           # Framework-Core (Router, Database, etc.)
│   ├── models/         # Model-Klassen
│   └── views/          # View-Templates
├── config/
│   └── config.php      # Zentrale Konfiguration
├── public/
│   ├── index.php       # Entry-Point
│   └── [assets]        # CSS, JS, etc.
└── storage/            # Logs, Cache, etc.
```

## 🔧 Konfiguration

### BASE_URL
Die BASE_URL wird automatisch erkannt. Bei Bedarf kann sie in der `.env` überschrieben werden:
```
BASE_URL=http://localhost/Beschaffung_FT/public
```

### Debug-Modus
Im Debug-Modus werden detaillierte Fehlermeldungen angezeigt:
```
APP_ENV=development
APP_DEBUG=true
```

Für Produktion:
```
APP_ENV=production
APP_DEBUG=false
```

## 👥 Rollen-System

1. **Admin** (rolle_id=1) - Vollzugriff
2. **IT** (rolle_id=2) - Hardware/Software-Verwaltung
3. **Vorzimmer** (rolle_id=3) - Benutzerverwaltung
4. **Personal** (rolle_id=4) - Mitarbeiter-Onboarding
5. **Hausmeister** (rolle_id=5) - Lesezugriff

## 🛠️ Migration alter Passwort-Hashes

Falls Sie alte SHA256-Hashes haben, werden diese automatisch erkannt und akzeptiert. 
Um auf password_hash zu migrieren, führen Sie das Skript aus:
```bash
php storage/migrate_passwords.php
```

## 📝 Best Practices

- **Niemals** Passwörter im Klartext speichern
- **Immer** CSRF-Tokens in Formularen verwenden
- **Immer** Input-Validierung durchführen
- **Immer** Prepared Statements für SQL verwenden
- **Niemals** `display_errors=1` in Produktion
- **Immer** Fehler in Log-Dateien schreiben

## 🐛 Troubleshooting

### "CSRF validation failed"
- Cookie-Einstellungen prüfen
- Session wurde zurückgesetzt
- Formular neu laden

### "Database connection failed"
- `.env` Datei prüfen
- SQL Server läuft?
- SQLSRV Extension installiert?

### "404 - Route not found"
- Apache mod_rewrite aktiv?
- BASE_URL korrekt?
- .htaccess vorhanden?

## 📞 Support

Bei Fragen oder Problemen wenden Sie sich an den Projektverantwortlichen.

## 📄 Lizenz

Dieses Projekt ist ein IHK-Prüfungsprojekt und dient ausschließlich Ausbildungszwecken.

---

**Version:** 1.0.0  
**Letzte Aktualisierung:** Dezember 2024  
**Status:** ✅ Produktionsbereit (lokal)

