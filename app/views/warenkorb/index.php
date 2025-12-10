<!-- ═══════════════════════════════════════════════════════════ -->
<!-- WARENKORB-ÜBERSICHT -->
<!-- Zeigt alle ausgewählten Hardware- und Software-Artikel -->
<!-- ═══════════════════════════════════════════════════════════ -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>🛒 Warenkorb</h1>
        <p class="text-muted">
            Mitarbeiter: <strong><?= htmlspecialchars($data['mitarbeiter']['vorname'] . ' ' . $data['mitarbeiter']['nachname'], ENT_QUOTES, 'UTF-8') ?></strong>
        </p>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>/mitarbeiter" class="btn btn-secondary">
            ← Zurück zur Übersicht
        </a>
    </div>
</div>

<?php
// Erfolgs- oder Fehlermeldungen anzeigen
if (isset($_GET['success']) && $_GET['success'] == '1') {
    echo '<div class="alert alert-success">✓ Artikel wurde hinzugefügt!</div>';
}
if (isset($_GET['error']) && $_GET['error'] == 'empty') {
    echo '<div class="alert alert-danger">⚠ Der Warenkorb ist leer! Bitte wählen Sie Hardware oder Software aus.</div>';
}
?>

<!-- ═══ HARDWARE-BEREICH ═══ -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">💻 Hardware</h4>
        <a href="<?php echo BASE_URL; ?>/warenkorb/hardware?mitarbeiter_id=<?= $data['mitarbeiter']['id'] ?>" 
           class="btn btn-sm btn-light">
            + Hardware hinzufügen
        </a>
    </div>
    <div class="card-body">
        <?php if (count($data['warenkorb']['hardware']) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kategorie</th>
                            <th>Bezeichnung</th>
                            <th>Anzahl</th>
                            <th>Hinweis</th>
                            <th>Standard</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['warenkorb']['hardware'] as $hw): ?>
                            <tr>
                                <td><?= htmlspecialchars($hw['kategorie'] ?? 'Sonstiges', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><strong><?= htmlspecialchars($hw['hardware_name'] ?? 'Unbenannt', ENT_QUOTES, 'UTF-8') ?></strong></td>
                                <td>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($hw['anzahl'] ?? '1', ENT_QUOTES, 'UTF-8') ?>x</span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= htmlspecialchars(($hw['hinweis'] ?? '') ?: '-', ENT_QUOTES, 'UTF-8') ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if ($hw['ist_standard']): ?>
                                        <span class="badge bg-success">Standard</span>
                                    <?php else: ?>
                                        <span class="badge bg-info">Individuell</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="post" action="<?php echo BASE_URL; ?>/warenkorb/removeHardware" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="id" value="<?= $hw['id'] ?>">
                                        <input type="hidden" name="mitarbeiter_id" value="<?= $data['mitarbeiter']['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Wirklich entfernen?')">
                                            🗑 Entfernen
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted text-center py-4">
                Noch keine Hardware ausgewählt.
                <a href="<?php echo BASE_URL; ?>/warenkorb/hardware?mitarbeiter_id=<?= $data['mitarbeiter']['id'] ?>">
                    Jetzt Hardware hinzufügen »
                </a>
            </p>
        <?php endif; ?>
    </div>
</div>

<!-- ═══ SOFTWARE-BEREICH ═══ -->
<div class="card mb-4">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">📦 Software</h4>
        <a href="<?php echo BASE_URL; ?>/warenkorb/software?mitarbeiter_id=<?= $data['mitarbeiter']['id'] ?>" 
           class="btn btn-sm btn-light">
            + Software hinzufügen
        </a>
    </div>
    <div class="card-body">
        <?php if (count($data['warenkorb']['software']) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Bezeichnung</th>
                            <th>Lizenztyp</th>
                            <th>Anzahl</th>
                            <th>Hinweis</th>
                            <th>Standard</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['warenkorb']['software'] as $sw): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($sw['software_name'] ?? 'Unbenannt', ENT_QUOTES, 'UTF-8') ?></strong></td>
                                <td><?= htmlspecialchars($sw['lizenztyp'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($sw['anzahl'] ?? '1', ENT_QUOTES, 'UTF-8') ?>x</span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= htmlspecialchars(($sw['hinweis'] ?? '') ?: '-', ENT_QUOTES, 'UTF-8') ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if ($sw['ist_standard']): ?>
                                        <span class="badge bg-success">Standard</span>
                                    <?php else: ?>
                                        <span class="badge bg-info">Individuell</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="post" action="<?php echo BASE_URL; ?>/warenkorb/removeSoftware" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="id" value="<?= $sw['id'] ?>">
                                        <input type="hidden" name="mitarbeiter_id" value="<?= $data['mitarbeiter']['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Wirklich entfernen?')">
                                            🗑 Entfernen
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted text-center py-4">
                Noch keine Software ausgewählt.
                <a href="<?php echo BASE_URL; ?>/warenkorb/software?mitarbeiter_id=<?= $data['mitarbeiter']['id'] ?>">
                    Jetzt Software hinzufügen »
                </a>
            </p>
        <?php endif; ?>
    </div>
</div>

<!-- ═══ BESTELLUNG ABSCHLIESSEN ═══ -->
<?php if (count($data['warenkorb']['hardware']) > 0 || count($data['warenkorb']['software']) > 0): ?>
    <div class="card border-success">
        <div class="card-body">
            <h5>✓ Zusammenfassung</h5>
            <p>
                <strong>Hardware:</strong> <?= count($data['warenkorb']['hardware']) ?> Artikel<br>
                <strong>Software:</strong> <?= count($data['warenkorb']['software']) ?> Lizenzen
            </p>
            <form method="post" action="<?php echo BASE_URL; ?>/warenkorb/abschliessen">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="mitarbeiter_id" value="<?= $data['mitarbeiter']['id'] ?>">
                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Bestellung wirklich abschließen?')">
                    ✓ Bestellung abschließen
                </button>
            </form>
            <small class="text-muted d-block mt-2">
                Nach dem Abschließen wird der Status auf "im Onboarding" gesetzt.
            </small>
        </div>
    </div>
<?php endif; ?>

<style>
    .table {
        font-size: 0.95rem;
    }
    
    .card-header h4 {
        font-size: 1.2rem;
    }
    
    .badge {
        font-size: 0.85rem;
    }
</style>

