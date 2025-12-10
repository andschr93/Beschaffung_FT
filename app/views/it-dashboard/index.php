<!-- IT-Dashboard - AdminLTE 4 Style -->

<!-- Begrüßung -->
<div class="mb-8">
    <h2 style="font-size: var(--text-3xl); font-weight: var(--font-bold); color: #343a40; margin-bottom: var(--space-2);">
        IT-Dashboard
    </h2>
    <p style="color: #6c757d; font-size: var(--text-base);">
        Übersicht aller wichtigen Kennzahlen
    </p>
</div>

<!-- Info Boxes (AdminLTE 4 Style) -->
<div class="stat-grid">
    <!-- Box 1: Offene Anfragen (Gelb) -->
    <div class="stat-card warning">
        <div class="stat-icon">⏳</div>
        <div class="stat-value"><?= $data['stats']['mitarbeiter_offen'] ?? 0 ?></div>
        <div class="stat-label">Offene Anfragen</div>
    </div>

    <!-- Box 2: In Bearbeitung (Blau) -->
    <div class="stat-card">
        <div class="stat-icon">🔄</div>
        <div class="stat-value"><?= $data['stats']['mitarbeiter_in_bearbeitung'] ?? 0 ?></div>
        <div class="stat-label">In Bearbeitung</div>
    </div>

    <!-- Box 3: Abgeschlossen (Grün) -->
    <div class="stat-card success">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?= $data['stats']['mitarbeiter_abgeschlossen'] ?? 0 ?></div>
        <div class="stat-label">Abgeschlossen</div>
    </div>

    <!-- Box 4: Gesamt (Türkis) -->
    <div class="stat-card info">
        <div class="stat-icon">👥</div>
        <div class="stat-value"><?= $data['stats']['mitarbeiter_gesamt'] ?? 0 ?></div>
        <div class="stat-label">Gesamt Mitarbeiter</div>
    </div>
</div>

<!-- Hardware/Software Info -->
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-6); margin-bottom: var(--space-8);">
    <div class="card">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: var(--text-sm); color: #6c757d; margin-bottom: var(--space-2);">
                    Hardware verfügbar
                </div>
                <div style="font-size: var(--text-3xl); font-weight: var(--font-bold); color: #495057;">
                    <?= $data['stats']['hardware_verfuegbar'] ?? 0 ?>
                </div>
            </div>
            <div style="font-size: 4rem; opacity: 0.15;">💻</div>
        </div>
    </div>
    
    <div class="card">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: var(--text-sm); color: #6c757d; margin-bottom: var(--space-2);">
                    Software verfügbar
                </div>
                <div style="font-size: var(--text-3xl); font-weight: var(--font-bold); color: #495057;">
                    <?= $data['stats']['software_verfuegbar'] ?? 0 ?>
                </div>
            </div>
            <div style="font-size: 4rem; opacity: 0.15;">📦</div>
        </div>
    </div>
</div>

<!-- Zwei-Spalten-Layout -->
<div style="display: grid; grid-template-columns: 1fr 400px; gap: var(--space-6);">
    
    <!-- Dringende Anfragen -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                🚨 Dringende Anfragen
            </h3>
            <p style="color: #6c757d; font-size: var(--text-sm); margin-top: var(--space-2); margin-bottom: 0;">
                Start in den nächsten 7 Tagen
            </p>
        </div>
        
        <?php if (empty($data['urgentMitarbeiter'])): ?>
            <div style="text-align: center; padding: var(--space-8); color: #6c757d;">
                <div style="font-size: 3rem; margin-bottom: var(--space-3); opacity: 0.5;">🎉</div>
                <p style="font-size: var(--text-base); font-weight: var(--font-medium); color: #495057;">
                    Keine dringenden Anfragen
                </p>
                <p style="font-size: var(--text-sm); color: #6c757d;">
                    Alles im grünen Bereich!
                </p>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: var(--space-4);">
                <?php foreach ($data['urgentMitarbeiter'] as $ma): ?>
                    <div style="padding: var(--space-4); border: 1px solid #dee2e6; border-radius: var(--radius-md); transition: all var(--transition-fast); background: white;" 
                         onmouseover="this.style.borderColor='#007bff'; this.style.backgroundColor='#f8f9fa';"
                         onmouseout="this.style.borderColor='#dee2e6'; this.style.backgroundColor='white';">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <h4 style="font-size: var(--text-lg); font-weight: var(--font-semibold); color: #495057; margin-bottom: var(--space-2);">
                                    <?= htmlspecialchars($ma['vorname'] . ' ' . $ma['nachname'], ENT_QUOTES, 'UTF-8') ?>
                                </h4>
                                <div style="font-size: var(--text-sm); color: #6c757d; margin-bottom: var(--space-2);">
                                    <strong>Start:</strong> <?= date('d.m.Y', strtotime($ma['startdatum'])) ?>
                                    <span class="badge badge-danger" style="margin-left: var(--space-2);">
                                        <?= $ma['tage_bis_start'] ?> Tag<?= $ma['tage_bis_start'] != 1 ? 'e' : '' ?>
                                    </span>
                                </div>
                                <span class="badge badge-<?= $ma['status'] == 'Offen' ? 'warning' : 'info' ?>">
                                    <?= htmlspecialchars($ma['status'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </div>
                            <a href="<?= BASE_URL ?>/warenkorb?mitarbeiter_id=<?= $ma['id'] ?>" class="btn btn-primary btn-sm">
                                🛒 Warenkorb
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Rechte Spalte -->
    <div style="display: flex; flex-direction: column; gap: var(--space-6);">
        
        <!-- Letzte Aktivitäten -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📋 Letzte Aktivitäten</h3>
            </div>
            
            <?php if (empty($data['recentActivities'])): ?>
                <p style="color: #6c757d; font-style: italic;">Keine Aktivitäten</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column;">
                    <?php foreach ($data['recentActivities'] as $index => $activity): ?>
                        <div style="padding: var(--space-3) 0; <?= $index < count($data['recentActivities']) - 1 ? 'border-bottom: 1px solid #dee2e6;' : '' ?>">
                            <div style="font-weight: var(--font-semibold); color: #495057; margin-bottom: var(--space-1);">
                                <?= htmlspecialchars($activity['vorname'] . ' ' . $activity['nachname'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                            <div style="font-size: var(--text-sm); color: #6c757d;">
                                <?php 
                                $date = $activity['erstellt_am'];
                                if ($date instanceof DateTime) {
                                    echo $date->format('d.m.Y H:i');
                                } else {
                                    echo date('d.m.Y H:i', strtotime($date));
                                }
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Schnellzugriffe -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">🔧 Schnellzugriffe</h3>
            </div>
            
            <div style="display: grid; gap: var(--space-2);">
                <a href="<?= BASE_URL ?>/mitarbeiter" class="btn btn-secondary" style="width: 100%; justify-content: flex-start;">
                    👥 Mitarbeiter
                </a>
                <a href="<?= BASE_URL ?>/hardware" class="btn btn-secondary" style="width: 100%; justify-content: flex-start;">
                    💻 Hardware
                </a>
                <a href="<?= BASE_URL ?>/software" class="btn btn-secondary" style="width: 100%; justify-content: flex-start;">
                    📦 Software
                </a>
            </div>
        </div>
        
    </div>
    
</div>

<style>
@media (max-width: 1200px) {
    div[style*="grid-template-columns: 1fr 400px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
