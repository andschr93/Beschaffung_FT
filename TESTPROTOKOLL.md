# 🧪 TESTPROTOKOLL - Beschaffungssystem Stadtverwaltung Frankenthal

**Projekt:** Beschaffungs- und Onboarding-System  
**Tester:** Andreas Schröder  
**Datum:** Dezember 2024  
**System:** Windows 10, XAMPP, PHP 8.x, SQL Server Express

---

## TESTFALL 01: Login-Funktion

**Ziel:** Benutzer kann sich mit E-Mail und Passwort anmelden

| Nr | Schritt | Erwartetes Ergebnis | Tatsächliches Ergebnis | Status |
|----|---------|---------------------|------------------------|--------|
| 1.1 | Browser öffnen: `http://localhost/Beschaffung_FT/public/` | Login-Seite wird angezeigt | | ⬜ |
| 1.2 | Ungültige E-Mail eingeben (z.B. "test") | Fehlermeldung: "Ungültige E-Mail" | | ⬜ |
| 1.3 | Gültige E-Mail + falsches Passwort | Fehlermeldung: "Login fehlgeschlagen" | | ⬜ |
| 1.4 | Korrekte Anmeldedaten eingeben | Weiterleitung zum Dashboard | | ⬜ |
| 1.5 | Session-Info prüfen | Name wird in Header angezeigt | | ⬜ |

**Screenshot:** [ ] Login-Seite, [ ] Erfolgreiches Login

---

## TESTFALL 02: Hardware-Stammdaten (CRUD)

**Ziel:** Hardware kann angelegt, angezeigt, bearbeitet und gelöscht werden

| Nr | Schritt | Erwartetes Ergebnis | Tatsächliches Ergebnis | Status |
|----|---------|---------------------|------------------------|--------|
| 2.1 | Menü: "Hardware" aufrufen | Liste aller Hardware wird angezeigt | | ⬜ |
| 2.2 | Button "Neue Hardware anlegen" klicken | Formular wird angezeigt | | ⬜ |
| 2.3 | Formular ausfüllen (Kategorie, Name) | Speichern ohne Fehler | | ⬜ |
| 2.4 | Zurück zur Liste | Neue Hardware erscheint in Liste | | ⬜ |
| 2.5 | "Bearbeiten" klicken | Formular mit vorausgefüllten Daten | | ⬜ |
| 2.6 | Name ändern und speichern | Änderung wird übernommen | | ⬜ |
| 2.7 | "Löschen" klicken | Bestätigungsdialog erscheint | | ⬜ |
| 2.8 | Löschen bestätigen | Hardware wird aus Liste entfernt | | ⬜ |

**Screenshot:** [ ] Hardware-Liste, [ ] Hardware anlegen, [ ] Hardware bearbeiten

---

## TESTFALL 03: Software-Stammdaten (CRUD)

**Ziel:** Software kann angelegt, angezeigt, bearbeitet und gelöscht werden

| Nr | Schritt | Erwartetes Ergebnis | Tatsächliches Ergebnis | Status |
|----|---------|---------------------|------------------------|--------|
| 3.1 | Menü: "Software" aufrufen | Liste aller Software wird angezeigt | | ⬜ |
| 3.2 | "Neue Software anlegen" klicken | Formular wird angezeigt | | ⬜ |
| 3.3 | Formular ausfüllen (Name, Beschreibung, Lizenztyp) | Speichern ohne Fehler | | ⬜ |
| 3.4 | Zurück zur Liste | Neue Software erscheint | | ⬜ |
| 3.5 | Software bearbeiten | Änderungen werden gespeichert | | ⬜ |
| 3.6 | Software löschen | Software wird entfernt | | ⬜ |

**Screenshot:** [ ] Software-Liste, [ ] Software anlegen

---

## TESTFALL 04: Mitarbeiter-Verwaltung

**Ziel:** Neue Mitarbeiter können angelegt und verwaltet werden

| Nr | Schritt | Erwartetes Ergebnis | Tatsächliches Ergebnis | Status |
|----|---------|---------------------|------------------------|--------|
| 4.1 | Menü: "Mitarbeiter" aufrufen | Liste aller Mitarbeiter | | ⬜ |
| 4.2 | "Neuen Mitarbeiter anlegen" | Formular mit allen Feldern | | ⬜ |
| 4.3 | Nur Vorname eingeben (Rest leer) | Fehlermeldung: "Nachname darf nicht leer sein" | | ⬜ |
| 4.4 | Ungültige E-Mail (z.B. "test@") | Fehlermeldung: "Ungültige E-Mail" | | ⬜ |
| 4.5 | Alle Pflichtfelder korrekt ausfüllen | Mitarbeiter wird gespeichert | | ⬜ |
| 4.6 | Status ist "angelegt" | Status wird korrekt angezeigt | | ⬜ |

**Screenshot:** [ ] Mitarbeiter-Liste, [ ] Mitarbeiter anlegen

---

## TESTFALL 05: Warenkorb - Hardware hinzufügen ⭐ (KERNFUNKTION!)

**Ziel:** Hardware kann einem Mitarbeiter zugeordnet werden

| Nr | Schritt | Erwartetes Ergebnis | Tatsächliches Ergebnis | Status |
|----|---------|---------------------|------------------------|--------|
| 5.1 | In Mitarbeiter-Liste auf "🛒 Warenkorb" klicken | Warenkorb-Übersicht öffnet sich | | ⬜ |
| 5.2 | Warenkorb ist leer | Meldung: "Noch keine Hardware ausgewählt" | | ⬜ |
| 5.3 | "+ Hardware hinzufügen" klicken | Hardware-Katalog wird angezeigt | | ⬜ |
| 5.4 | Standard-Hardware hat ⭐ Badge | Visuell erkennbar | | ⬜ |
| 5.5 | Hardware auswählen, Anzahl=2, Hinweis eingeben | Formular ausgefüllt | | ⬜ |
| 5.6 | "Zum Warenkorb" klicken | Erfolgsmeldung: "Hardware hinzugefügt" | | ⬜ |
| 5.7 | Hardware hat "✓ Im Warenkorb" Badge | Badge wird angezeigt | | ⬜ |
| 5.8 | Zurück zum Warenkorb | Hardware erscheint in Tabelle mit Anzahl=2 | | ⬜ |

**Screenshot:** [ ] Hardware-Katalog, [ ] Hardware im Warenkorb

---

## TESTFALL 06: Warenkorb - Software hinzufügen ⭐ (KERNFUNKTION!)

**Ziel:** Software kann einem Mitarbeiter zugeordnet werden

| Nr | Schritt | Erwartetes Ergebnis | Tatsächliches Ergebnis | Status |
|----|---------|---------------------|------------------------|--------|
| 6.1 | Im Warenkorb "+ Software hinzufügen" | Software-Katalog wird angezeigt | | ⬜ |
| 6.2 | Software auswählen, Anzahl=1 | Formular ausgefüllt | | ⬜ |
| 6.3 | "Zum Warenkorb" klicken | Erfolgsmeldung erscheint | | ⬜ |
| 6.4 | Zurück zum Warenkorb | Software erscheint in Tabelle | | ⬜ |
| 6.5 | Lizenztyp wird angezeigt | Korrekt (z.B. "Volumenlizenz") | | ⬜ |

**Screenshot:** [ ] Software-Katalog, [ ] Software im Warenkorb

---

## TESTFALL 07: Warenkorb - Artikel entfernen ⭐

**Ziel:** Artikel können aus dem Warenkorb entfernt werden

| Nr | Schritt | Erwartetes Ergebnis | Tatsächliches Ergebnis | Status |
|----|---------|---------------------|------------------------|--------|
| 7.1 | Bei Hardware auf "🗑 Entfernen" klicken | Bestätigungsdialog erscheint | | ⬜ |
| 7.2 | Bestätigen mit "OK" | Hardware wird aus Warenkorb entfernt | | ⬜ |
| 7.3 | Seite neu laden | Hardware ist wirklich weg | | ⬜ |
| 7.4 | Dasselbe mit Software | Software wird entfernt | | ⬜ |

**Screenshot:** [ ] Artikel entfernen (Bestätigung)

---

## TESTFALL 08: Bestellung abschließen ⭐ (KERNFUNKTION!)

**Ziel:** Warenkorb kann abgeschlossen werden, Status ändert sich

| Nr | Schritt | Erwartetes Ergebnis | Tatsächliches Ergebnis | Status |
|----|---------|---------------------|------------------------|--------|
| 8.1 | Mindestens 1 Artikel im Warenkorb | Zusammenfassung wird angezeigt | | ⬜ |
| 8.2 | Button "✓ Bestellung abschließen" sichtbar | Button ist da | | ⬜ |
| 8.3 | Button klicken | Bestätigungsdialog erscheint | | ⬜ |
| 8.4 | Bestätigen | Weiterleitung zu Mitarbeiter-Liste | | ⬜ |
| 8.5 | Status des Mitarbeiters prüfen | Status = "im Onboarding" | | ⬜ |
| 8.6 | Warenkorb erneut öffnen | Artikel sind noch da (persistent) | | ⬜ |

**Screenshot:** [ ] Zusammenfassung, [ ] Bestellung abschließen, [ ] Status geändert

---

## TESTFALL 09: Benutzer-Verwaltung (Admin/Vorzimmer)

**Ziel:** Nur Admin und Vorzimmer können User verwalten

| Nr | Schritt | Erwartetes Ergebnis | Tatsächliches Ergebnis | Status |
|----|---------|---------------------|------------------------|--------|
| 9.1 | Als Admin einloggen | Login erfolgreich | | ⬜ |
| 9.2 | Menü: "Benutzerverwaltung" | Liste aller User | | ⬜ |
| 9.3 | "Neuen User anlegen" | Formular wird angezeigt | | ⬜ |
| 9.4 | Passwort kürzer als 8 Zeichen | Fehlermeldung erscheint | | ⬜ |
| 9.5 | Korrektes Passwort (mind. 8 Zeichen) | User wird angelegt | | ⬜ |
| 9.6 | User deaktivieren | Status wechselt auf "Nein" | | ⬜ |
| 9.7 | User wieder aktivieren | Status wechselt auf "Ja" | | ⬜ |

**Screenshot:** [ ] User-Verwaltung, [ ] User anlegen

---

## TESTFALL 10: Sicherheit - CSRF-Schutz

**Ziel:** CSRF-Attacken werden blockiert

| Nr | Schritt | Erwartetes Ergebnis | Tatsächliches Ergebnis | Status |
|----|---------|---------------------|------------------------|--------|
| 10.1 | Formular ohne CSRF-Token absenden (manuell) | Fehler: "CSRF-Validierung fehlgeschlagen" | | ⬜ |
| 10.2 | Mit gültigem Token | Funktioniert normal | | ⬜ |

**Screenshot:** [ ] CSRF-Fehler

---

## TESTFALL 11: Sicherheit - Rate-Limiting

**Ziel:** Nach 5 Fehlversuchen wird Login gesperrt

| Nr | Schritt | Erwartetes Ergebnis | Tatsächliches Ergebnis | Status |
|----|---------|---------------------|------------------------|--------|
| 11.1 | 5x falsches Passwort eingeben | Fehlermeldungen | | ⬜ |
| 11.2 | 6. Versuch | Meldung: "Zu viele Fehlversuche... 15 Minuten gesperrt" | | ⬜ |
| 11.3 | Auch mit richtigem Passwort | Gesperrt bleibt | | ⬜ |

**Screenshot:** [ ] Rate-Limit-Meldung

---

## TESTFALL 12: Bereich-Abteilung-Validierung

**Ziel:** Abteilung muss zum gewählten Bereich passen

| Nr | Schritt | Erwartetes Ergebnis | Tatsächliches Ergebnis | Status |
|----|---------|---------------------|------------------------|--------|
| 12.1 | Mitarbeiter anlegen: Bereich A, Abteilung von Bereich B | Fehlermeldung erscheint | | ⬜ |
| 12.2 | Bereich A, Abteilung von Bereich A | Erfolgreich gespeichert | | ⬜ |

**Screenshot:** [ ] Validierungs-Fehler

---

## 📊 GESAMT-ERGEBNIS

| Kategorie | Anzahl Tests | Bestanden | Fehlgeschlagen | Quote |
|-----------|--------------|-----------|----------------|-------|
| Login/Auth | 2 | | | |
| Stammdaten | 2 | | | |
| Mitarbeiter | 1 | | | |
| Warenkorb | 4 | | | |
| Sicherheit | 2 | | | |
| Validierung | 1 | | | |
| **GESAMT** | **12** | | | |

---

## 🐛 GEFUNDENE FEHLER

| Nr | Beschreibung | Schwere | Status | Gelöst am |
|----|--------------|---------|--------|-----------|
| 1 | | | | |

---

## ✅ FAZIT

[Wird nach Tests ausgefüllt]

---

**Unterschrift Tester:** ________________  
**Datum:** ________________

