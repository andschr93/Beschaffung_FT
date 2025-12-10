<div style="margin-bottom: var(--space-6);">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: var(--text-3xl); font-weight: var(--font-bold); margin: 0;">
            👥 Mitarbeiter-Übersicht
        </h2>
        <a href="<?= BASE_URL ?>/mitarbeiter/create" class="btn btn-primary">
            ➕ Neuen Mitarbeiter anlegen
        </a>
    </div>
</div>

<div class="table-container" style="overflow-x: auto;">
    <table style="min-width: 100%; width: max-content;">
        <thead>
            <tr>
                <th style="min-width: 60px;">ID</th>
                <th style="min-width: 100px;">Vorname</th>
                <th style="min-width: 100px;">Nachname</th>
                <th style="min-width: 200px;">Email</th>
                <th style="min-width: 120px;">Bereich</th>
                <th style="min-width: 120px;">Startdatum</th>
                <th style="min-width: 120px;">Status</th>
                <th style="min-width: 180px; position: sticky; right: 0; background: white; box-shadow: -4px 0 8px rgba(0,0,0,0.1); z-index: 10;">Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data['list'])): ?>
                <tr>
                    <td colspan="12" style="text-align: center; padding: var(--space-8); color: var(--gray-500);">
                        <div style="font-size: 3rem; margin-bottom: var(--space-4);">📭</div>
                        <p>Noch keine Mitarbeiter angelegt.</p>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($data['list'] as $m): ?>
                    <tr>
                        <td style="white-space: nowrap;"><?= htmlspecialchars($m['id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="white-space: nowrap;"><?= htmlspecialchars($m['vorname'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="white-space: nowrap;"><?= htmlspecialchars($m['nachname'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="white-space: nowrap; max-width: 200px; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($m['email'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($m['email'], ENT_QUOTES, 'UTF-8') ?>
                        </td>
                        <td style="white-space: nowrap;"><?= htmlspecialchars($m['bereich_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="white-space: nowrap;">
                            <?php 
                            $datum = $m['startdatum'] ?? '';
                            if ($datum instanceof DateTime) {
                                $datum = $datum->format('d.m.Y');
                            }
                            echo htmlspecialchars($datum, ENT_QUOTES, 'UTF-8'); 
                            ?>
                        </td>
                        <td style="white-space: nowrap;">
                            <span class="badge badge-<?= 
                                ($m['status'] ?? '') === 'Offen' ? 'warning' : 
                                (($m['status'] ?? '') === 'Abgeschlossen' ? 'success' : 'info') 
                            ?>">
                                <?= htmlspecialchars($m['status'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td style="white-space: nowrap; position: sticky; right: 0; background: inherit;">
                            <div style="display: flex; gap: var(--space-2); align-items: center;">
                                <a class="btn btn-info btn-sm" 
                                   href="<?= BASE_URL ?>/warenkorb?mitarbeiter_id=<?= htmlspecialchars($m['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    🛒 Warenkorb
                                </a>
                                <a class="btn btn-warning btn-sm" 
                                   href="<?= BASE_URL ?>/mitarbeiter/edit?id=<?= htmlspecialchars($m['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    ✏️ Bearbeiten
                                </a>
                                <form method="post" action="<?= BASE_URL ?>/mitarbeiter/delete" style="display:inline; margin: 0;">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($m['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Wirklich löschen?')">
                                        🗑️ Löschen
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
