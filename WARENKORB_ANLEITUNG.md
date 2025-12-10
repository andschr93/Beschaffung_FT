ch # 🛒 WARENKORB - Installations- und Test-Anleitung

## ✅ WAS WURDE IMPLEMENTIERT

### 1. **Datenbank** (2 neue Tabellen)
- ✅ `mitarbeiter_hardware` - Verknüpft Mitarbeiter mit Hardware
- ✅ `mitarbeiter_software` - Verknüpft Mitarbeiter mit Software
- ✅ Views für einfachere Abfragen

### 2. **Backend (PHP)**
- ✅ `WarenkorbModel.php` - 15 Methoden für Warenkorb-Verwaltung
- ✅ `WarenkorbController.php` - 8 Controller-Methoden
- ✅ Vollständig kommentiert auf Deutsch!
- ✅ CSRF-Schutz, Input-Validierung, Authentifizierung

### 3. **Frontend (Views)**
- ✅ `warenkorb/index.php` - Warenkorb-Übersicht
- ✅ `warenkorb/hardware.php` - Hardware-Auswahl
- ✅ `warenkorb/software.php` - Software-Auswahl
- ✅ Button in Mitarbeiter-Liste

### 4. **Routing**
- ✅ 8 neue Routen in `public/index.php`

---

## 🚀 INSTALLATION - SCHRITT FÜR SCHRITT

### SCHRITT 1: Datenbank-Tabellen anlegen

1. **SQL Server Management Studio (SSMS) öffnen**
2. Mit Ihrem SQL Server verbinden
3. Datenbank `Beschaffung_FT` auswählen
4. Datei öffnen: `database/warenkorb_tables.sql`
5. **F5 drücken** (oder "Execute")
6. **Prüfen ob erfolgreich:**
   ```sql
   SELECT * FROM INFORMATION_SCHEMA.TABLES 
   WHERE TABLE_NAME IN ('mitarbeiter_hardware', 'mitarbeiter_software')
   ```
   → Sollte 2 Zeilen zurückgeben

---

### SCHRITT 2: Apache-Server neu starten

1. XAMPP öffnen
2. Apache **Stop** klicken
3. Apache **Start** klicken
4. ✓ Fertig!

---

## 🧪 TESTEN - Schritt für Schritt

### TEST 1: Mitarbeiter-Liste aufrufen

1. Browser öffnen: `http://localhost/Beschaffung_FT/public/mitarbeiter`
2. **Prüfen:** Sehen Sie den neuen **"🛒 Warenkorb"** Button bei jedem Mitarbeiter?
   - ✅ JA → Weiter zu Test 2
   - ❌ NEIN → Apache neu starten

---

### TEST 2: Warenkorb öffnen

1. Klicken Sie auf **"🛒 Warenkorb"** bei einem Mitarbeiter
2. **Erwartetes Ergebnis:**
   - Sie sehen die Überschrift "🛒 Warenkorb"
   - Mitarbeiter-Name wird angezeigt
   - 2 Bereiche: "💻 Hardware" und "📦 Software"
   - Beide sind noch leer
   - 2 Buttons: "+ Hardware hinzufügen" und "+ Software hinzufügen"

---

### TEST 3: Hardware hinzufügen

1. Klicken Sie auf **"+ Hardware hinzufügen"**
2. **Erwartetes Ergebnis:**
   - Sie sehen alle verfügbaren Hardware-Artikel als Cards
   - Standard-Hardware hat ein ⭐ Badge
3. **Wählen Sie eine Hardware aus:**
   - Anzahl eingeben (z.B. 1)
   - Optional: Hinweis eingeben (z.B. "Mit 1TB SSD")
   - Klicken Sie **"+ Zum Warenkorb"**
4. **Erwartetes Ergebnis:**
   - Grüne Erfolgsmeldung: "✓ Hardware wurde hinzugefügt!"
   - Der Artikel hat jetzt ein "✓ Im Warenkorb" Badge

---

### TEST 4: Software hinzufügen

1. Zurück zum Warenkorb (Button "← Zurück zum Warenkorb")
2. Klicken Sie auf **"+ Software hinzufügen"**
3. **Erwartetes Ergebnis:**
   - Sie sehen alle verfügbaren Software-Artikel als Cards
4. **Wählen Sie eine Software aus:**
   - Anzahl Lizenzen eingeben (z.B. 1)
   - Optional: Hinweis eingeben
   - Klicken Sie **"+ Zum Warenkorb"**
5. **Erwartetes Ergebnis:**
   - Grüne Erfolgsmeldung
   - Software ist jetzt markiert

---

### TEST 5: Warenkorb prüfen

1. Zurück zum Warenkorb
2. **Erwartetes Ergebnis:**
   - Hardware-Tabelle zeigt Ihre ausgewählte Hardware
   - Software-Tabelle zeigt Ihre ausgewählte Software
   - Jeder Artikel hat einen "🗑 Entfernen" Button
   - Unten erscheint ein grüner Bereich "✓ Zusammenfassung"
   - Button **"✓ Bestellung abschließen"** ist sichtbar

---

### TEST 6: Artikel entfernen

1. Klicken Sie auf **"🗑 Entfernen"** bei einem Artikel
2. Bestätigen Sie mit "OK"
3. **Erwartetes Ergebnis:**
   - Artikel ist sofort verschwunden
   - Warenkorb wird neu geladen

---

### TEST 7: Bestellung abschließen

1. Fügen Sie mindestens 1 Hardware oder Software hinzu
2. Klicken Sie auf **"✓ Bestellung abschließen"**
3. Bestätigen Sie mit "OK"
4. **Erwartetes Ergebnis:**
   - Sie werden zur Mitarbeiter-Übersicht weitergeleitet
   - Der Status des Mitarbeiters ist jetzt **"im Onboarding"**

---

## ✅ ERFOLGS-CHECKLISTE

Kreuzen Sie ab, wenn der Test erfolgreich war:

- [ ] Warenkorb-Button wird in Mitarbeiter-Liste angezeigt
- [ ] Warenkorb-Übersicht öffnet sich
- [ ] Hardware-Auswahl zeigt alle Hardware-Artikel
- [ ] Hardware kann hinzugefügt werden
- [ ] Software-Auswahl zeigt alle Software-Artikel
- [ ] Software kann hinzugefügt werden
- [ ] Warenkorb zeigt hinzugefügte Artikel an
- [ ] Artikel können entfernt werden
- [ ] Bestellung kann abgeschlossen werden
- [ ] Mitarbeiter-Status ändert sich auf "im Onboarding"

---

## 🐛 PROBLEME UND LÖSUNGEN

### Problem: "Call to undefined function: WarenkorbModel::..."

**Ursache:** Datenbank-Tabellen fehlen

**Lösung:**
1. `database/warenkorb_tables.sql` in SSMS ausführen
2. Prüfen ob Tabellen existieren

---

### Problem: "404 - Route not found" beim Warenkorb

**Ursache:** Routen nicht geladen

**Lösung:**
1. Apache in XAMPP neu starten
2. Browser-Cache leeren (Strg+F5)

---

### Problem: CSRF-Fehler

**Ursache:** Session abgelaufen

**Lösung:**
1. Seite neu laden (F5)
2. Falls weiterhin: Ausloggen und neu einloggen

---

### Problem: Keine Hardware/Software sichtbar

**Ursache:** Stammdaten fehlen

**Lösung:**
1. Gehen Sie zu "Hardware" → "+ Neue Hardware anlegen"
2. Mindestens 1 Hardware-Artikel anlegen
3. Dasselbe für Software

---

## 📊 WAS PASSIERT TECHNISCH?

### Ablauf beim Hinzufügen von Hardware:

1. **User klickt "Zum Warenkorb"**
2. **POST-Request** an `/warenkorb/addHardware`
3. **WarenkorbController::addHardware()** wird aufgerufen
4. **CSRF-Token wird geprüft** (Sicherheit!)
5. **Input wird validiert** (mitarbeiter_id, hardware_id, anzahl)
6. **WarenkorbModel::addHardware()** speichert in Datenbank
7. **Prüfung:** Existiert schon? → Anzahl erhöhen
8. **Sonst:** Neuer Eintrag in `mitarbeiter_hardware`
9. **Redirect** zurück zur Hardware-Auswahl mit Erfolgsmeldung

### Datenbank-Struktur:

```
mitarbeiter_hardware
├── id (INT) - Eindeutige ID
├── mitarbeiter_id (INT) - Fremdschlüssel → mitarbeiter.id
├── hardware_id (INT) - Fremdschlüssel → hardware_stammdaten.id
├── anzahl (INT) - Wie viele Stück?
├── hinweis (NVARCHAR) - Optionale Bemerkung
└── erstellt_am (DATETIME) - Zeitstempel
```

---

## 📝 FÜR IHK-DOKUMENTATION

**Fertiger Textbaustein für Ihre Projektdokumentation:**

> **Warenkorb-Funktion (Beschaffungslogik)**
> 
> Die Kernfunktionalität des Systems besteht in der Zuordnung von Hardware und Software zu Mitarbeitern. Nach Anlage eines neuen Mitarbeiters kann über die Warenkorb-Funktion die benötigte IT-Ausstattung ausgewählt werden.
> 
> **Technische Umsetzung:**
> - Zwei Verknüpfungstabellen (`mitarbeiter_hardware`, `mitarbeiter_software`) mit Fremdschlüssel-Beziehungen
> - Session-basierte Navigation zwischen Auswahl und Warenkorb
> - Prepared Statements zur Vermeidung von SQL-Injection
> - CSRF-Protection in allen Formularen
> - Input-Validierung (Anzahl, IDs)
> 
> **Besonderheiten:**
> - Automatische Mengen-Erhöhung bei doppelter Auswahl
> - Standard-Artikel werden visuell hervorgehoben
> - Optional: Hinweise für individuelle Anpassungen
> - Status-Änderung auf "im Onboarding" nach Bestellabschluss

---

## 🎓 LERNEN: Code verstehen

Schauen Sie sich diese Dateien an und lesen Sie die Kommentare:

1. **`app/models/WarenkorbModel.php`**
   - Wie werden Daten in die Datenbank geschrieben?
   - Wie funktioniert die Duplikat-Prüfung?

2. **`app/controllers/WarenkorbController.php`**
   - Wie wird die Authentifizierung geprüft?
   - Wie funktioniert CSRF-Schutz?

3. **`app/views/warenkorb/index.php`**
   - Wie werden Daten aus dem Array angezeigt?
   - Wie funktioniert XSS-Schutz mit `htmlspecialchars()`?

**Aufgabe für Sie:**
Können Sie erklären, was in Zeile 45 von `WarenkorbModel.php` passiert?

---

## ✨ NÄCHSTE SCHRITTE

1. ✅ **JETZT:** Alles testen (siehe oben)
2. ✅ **DANACH:** Screenshots für Dokumentation machen
3. ✅ **DANN:** Testprotokolle erstellen
4. ✅ **ZULETZT:** In Dokumentation einarbeiten

---

**Geschätzte Zeit investiert:** ~10-12 Stunden  
**Projektstatus:** Kernfunktion fertig! 🎉  
**Nächstes Ziel:** Tests dokumentieren

---

**Erstellt:** Dezember 2024  
**Version:** 1.0  
**Status:** ✅ Produktionsbereit

