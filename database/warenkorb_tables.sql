-- ═══════════════════════════════════════════════════════════════
-- WARENKORB-TABELLEN FÜR BESCHAFFUNGSSYSTEM
-- ═══════════════════════════════════════════════════════════════
-- Diese Tabellen verbinden Mitarbeiter mit Hardware und Software
-- Damit kann man pro Mitarbeiter sehen, was bestellt wurde.
-- ═══════════════════════════════════════════════════════════════

-- TABELLE 1: Hardware-Zuordnung zu Mitarbeitern
-- Hier wird gespeichert, welche Hardware ein Mitarbeiter bekommt
CREATE TABLE mitarbeiter_hardware (
    id INT IDENTITY(1,1) PRIMARY KEY,  -- Automatisch hochzählende ID
    mitarbeiter_id INT NOT NULL,       -- Welcher Mitarbeiter? (Fremdschlüssel zu mitarbeiter)
    hardware_id INT NOT NULL,          -- Welche Hardware? (Fremdschlüssel zu hardware_stammdaten)
    anzahl INT DEFAULT 1,              -- Wie viele Stück? (Standard: 1)
    hinweis NVARCHAR(500) NULL,        -- Optionale Bemerkung (z.B. "SSD 1TB statt 512GB")
    erstellt_am DATETIME DEFAULT GETDATE(),  -- Wann wurde es hinzugefügt?
    
    -- Fremdschlüssel-Beziehungen definieren
    CONSTRAINT FK_MitarbeiterHardware_Mitarbeiter 
        FOREIGN KEY (mitarbeiter_id) 
        REFERENCES mitarbeiter(id) 
        ON DELETE CASCADE,  -- Wenn Mitarbeiter gelöscht wird, auch diese Einträge löschen
    
    CONSTRAINT FK_MitarbeiterHardware_Hardware 
        FOREIGN KEY (hardware_id) 
        REFERENCES hardware_stammdaten(id)
        ON DELETE CASCADE
);

-- Index für schnellere Abfragen (wichtig bei vielen Datensätzen)
CREATE INDEX IX_MitarbeiterHardware_MitarbeiterId 
    ON mitarbeiter_hardware(mitarbeiter_id);

-- ═══════════════════════════════════════════════════════════════

-- TABELLE 2: Software-Zuordnung zu Mitarbeitern
-- Hier wird gespeichert, welche Software ein Mitarbeiter bekommt
CREATE TABLE mitarbeiter_software (
    id INT IDENTITY(1,1) PRIMARY KEY,  -- Automatisch hochzählende ID
    mitarbeiter_id INT NOT NULL,       -- Welcher Mitarbeiter? (Fremdschlüssel zu mitarbeiter)
    software_id INT NOT NULL,          -- Welche Software? (Fremdschlüssel zu software_stammdaten)
    anzahl INT DEFAULT 1,              -- Wie viele Lizenzen? (Standard: 1)
    hinweis NVARCHAR(500) NULL,        -- Optionale Bemerkung
    erstellt_am DATETIME DEFAULT GETDATE(),  -- Wann wurde es hinzugefügt?
    
    -- Fremdschlüssel-Beziehungen definieren
    CONSTRAINT FK_MitarbeiterSoftware_Mitarbeiter 
        FOREIGN KEY (mitarbeiter_id) 
        REFERENCES mitarbeiter(id) 
        ON DELETE CASCADE,
    
    CONSTRAINT FK_MitarbeiterSoftware_Software 
        FOREIGN KEY (software_id) 
        REFERENCES software_stammdaten(id)
        ON DELETE CASCADE
);

-- Index für schnellere Abfragen
CREATE INDEX IX_MitarbeiterSoftware_MitarbeiterId 
    ON mitarbeiter_software(mitarbeiter_id);

-- ═══════════════════════════════════════════════════════════════
GO
-- WICHTIG: GO trennt die Batches, damit CREATE VIEW funktioniert
-- ═══════════════════════════════════════════════════════════════

-- OPTIONAL: View für einfachere Abfragen
-- Diese View zeigt alle Hardware eines Mitarbeiters mit Details an
CREATE VIEW v_mitarbeiter_hardware_details AS
SELECT 
    mh.id,
    mh.mitarbeiter_id,
    m.vorname,
    m.nachname,
    m.email,
    hw.id AS hardware_id,
    hw.kategorie,
    hw.name AS hardware_name,
    mh.anzahl,
    mh.hinweis,
    mh.erstellt_am
FROM mitarbeiter_hardware mh
INNER JOIN mitarbeiter m ON mh.mitarbeiter_id = m.id
INNER JOIN hardware_stammdaten hw ON mh.hardware_id = hw.id;

-- ═══════════════════════════════════════════════════════════════
GO
-- Neuer Batch für die zweite View
-- ═══════════════════════════════════════════════════════════════

-- View für Software-Details
CREATE VIEW v_mitarbeiter_software_details AS
SELECT 
    ms.id,
    ms.mitarbeiter_id,
    m.vorname,
    m.nachname,
    m.email,
    sw.id AS software_id,
    sw.name AS software_name,
    sw.lizenztyp,
    ms.anzahl,
    ms.hinweis,
    ms.erstellt_am
FROM mitarbeiter_software ms
INNER JOIN mitarbeiter m ON ms.mitarbeiter_id = m.id
INNER JOIN software_stammdaten sw ON ms.software_id = sw.id;

-- ═══════════════════════════════════════════════════════════════
GO
-- Script abgeschlossen - alle Views wurden erstellt
-- ═══════════════════════════════════════════════════════════════

-- ANLEITUNG ZUR AUSFÜHRUNG:
-- 1. Öffnen Sie SQL Server Management Studio (SSMS)
-- 2. Verbinden Sie sich mit Ihrem SQL Server
-- 3. Wählen Sie die Datenbank "Beschaffung_FT" aus
-- 4. Führen Sie dieses Script aus (F5 oder "Execute")
-- 5. Prüfen Sie ob Tabellen erstellt wurden:
--    SELECT * FROM INFORMATION_SCHEMA.TABLES 
--    WHERE TABLE_NAME IN ('mitarbeiter_hardware', 'mitarbeiter_software')
-- ═══════════════════════════════════════════════════════════════

