<h2>Software-Stammdaten</h2>
<a href="<?php echo BASE_URL; ?>/software/create" class="btn btn-primary mb-3">Neue Software anlegen</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Beschreibung</th>
            <th>Lizenztyp</th>
            <th>Standard?</th>
            <th>Aktiv</th>
            <th>Aktionen</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data['list'] as $sw): ?>
            <tr>
                <td><?= htmlspecialchars($sw['id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($sw['name'] ?? 'Unbenannt', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($sw['beschreibung'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($sw['lizenztyp'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= $sw['ist_standard'] ? 'Ja' : 'Nein' ?></td>
                <td><?= $sw['aktiv'] ? 'Ja' : 'Nein' ?></td>
                <td>
                    <a href="<?php echo BASE_URL; ?>/software/edit?id=<?= htmlspecialchars($sw['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-warning btn-sm">Bearbeiten</a>
                    <form method="post" action="<?php echo BASE_URL; ?>/software/delete" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($sw['id'], ENT_QUOTES, 'UTF-8') ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Wirklich löschen?')">Löschen</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
