<h2>Benutzerverwaltung</h2>
<a href="<?php echo BASE_URL; ?>/users/create" class="btn btn-primary mb-3">Neuen User anlegen</a>
<table class="table table-striped">
    <thead><tr>
        <th>ID</th>
        <th>Vorname</th>
        <th>Nachname</th>
        <th>E-Mail</th>
        <th>Rolle</th>
        <th>Aktiv</th>
        <th>Aktionen</th>
    </tr></thead>
    <tbody>
        <?php foreach($data['list'] as $user): ?>
            <tr>
              <td><?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($user['vorname'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($user['nachname'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($user['rolle_name'], ENT_QUOTES, 'UTF-8') ?></td>
              <td>
                <?= $user['aktiv'] ? 'Ja' : 'Nein' ?>
                <form method="post" action="<?php echo BASE_URL; ?>/users/setActive" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>">
                    <?php if ($user['aktiv']): ?>
                        <input type="hidden" name="active" value="0">
                        <button type="submit" class="btn btn-sm btn-warning">Deaktivieren</button>
                    <?php else: ?>
                        <input type="hidden" name="active" value="1">
                        <button type="submit" class="btn btn-sm btn-success">Aktivieren</button>
                    <?php endif; ?>
                </form>
              </td>
              <td>
                <a class="btn btn-sm btn-info" href="<?php echo BASE_URL; ?>/users/edit?id=<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>">Bearbeiten</a>
              </td>
           </tr>
        <?php endforeach; ?>
    </tbody>
</table>
