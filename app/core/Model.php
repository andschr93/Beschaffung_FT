<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * BASIS-MODEL-KLASSE
 * ═══════════════════════════════════════════════════════════════
 * 
 * Diese Klasse ist die "Eltern-Klasse" für alle Models.
 * Alle anderen Models (User, Hardware, Software, etc.) erben von ihr.
 * 
 * AKTUELL:
 * - Die Klasse ist leer (keine Methoden/Properties)
 * - Sie dient nur als Basis für zukünftige Erweiterungen
 * 
 * MÖGLICHE ZUKÜNFTIGE ERWEITERUNGEN:
 * - Gemeinsame Methoden für alle Models (z.B. validate())
 * - Gemeinsame Properties (z.B. $tableName)
 * - Fehlerbehandlung (z.B. $errors Array)
 * 
 * WARUM TROTZDEM VERWENDEN?
 * - Objektorientierte Best Practice (Vererbungshierarchie)
 * - Flexibilität für spätere Änderungen
 * - Alle Models haben eine gemeinsame Basis
 * 
 * VERWENDUNG IN ANDEREN MODELS:
 * ```php
 * class UserModel extends Model {
 *     // UserModel erbt von Model
 *     // Kann alle Methoden von Model verwenden (aktuell keine)
 * }
 * ```
 * 
 * ═══════════════════════════════════════════════════════════════
 */

class Model {
    // Aktuell keine Implementierung
    // Diese Klasse ist ein "Platzhalter" für zukünftige gemeinsame Funktionalität
}

// ═══════════════════════════════════════════════════════════════
// ENDE DER BASIS-MODEL-KLASSE
// 
// WICHTIGE KONZEPTE FÜR IHK-PRÜFUNG:
// 
// 1. VERERBUNG (INHERITANCE):
//    - Basis-Klasse (Parent) → Model
//    - Abgeleitete Klassen (Children) → UserModel, HardwareModel, etc.
//    - "extends" = erbt alle Methoden und Properties
//    - Vorteile: Code-Wiederverwendung, einheitliche Struktur
// 
// 2. ABSTRAKTE KLASSEN vs. LEERE KLASSEN:
//    - Diese Klasse ist NICHT abstract (könnte instanziiert werden)
//    - Aber sie wird nie direkt verwendet, nur als Basis
//    - Alternative: abstract class Model {} (kann nicht instanziiert werden)
// 
// 3. OBJEKTORIENTIERTE ARCHITEKTUR:
//    - Alle Models haben gleiche Basis → einheitliche Struktur
//    - Erleichtert zukünftige Erweiterungen
//    - Zeigt professionelle Code-Organisation
// 
// ═══════════════════════════════════════════════════════════════
