<h2>Hardware-Stammdaten</h2>

<a href="<?php echo BASE_URL; ?>/hardware/create" class="btn btn-primary mb-3">Neue Hardware anlegen</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Kategorie</th>
            <th>Name</th>
            <th>Standard?</th>
            <th>Aktiv</th>
            <th>Aktionen</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['list'] as $hw): ?>
            <tr>
                <td><?= htmlspecialchars($hw['id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($hw['kategorie'] ?? 'Sonstiges', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($hw['name'] ?? 'Unbenannt', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= $hw['ist_standard'] ? 'Ja' : 'Nein' ?></td>
                <td><?= $hw['aktiv'] ? 'Ja' : 'Nein' ?></td>
                <td>
                    <a href="<?php echo BASE_URL; ?>/hardware/edit?id=<?= htmlspecialchars($hw['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm btn-warning">Bearbeiten</a>
                    <form method="post" action="<?php echo BASE_URL; ?>/hardware/delete" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($hw['id'], ENT_QUOTES, 'UTF-8') ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Wirklich löschen?')">Löschen</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
