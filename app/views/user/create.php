<h2>Neuen Benutzer anlegen</h2>
<?php if (!empty($data['error'])) { echo '<div class="alert alert-danger">'.htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8').'</div>'; } ?>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
    
    <div class="mb-3">
        <label class="form-label">Vorname*</label>
        <input type="text" name="vorname" class="form-control" required maxlength="100">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Nachname*</label>
        <input type="text" name="nachname" class="form-control" required maxlength="100">
    </div>
    
    <div class="mb-3">
        <label class="form-label">E-Mail*</label>
        <input type="email" name="email" class="form-control" required maxlength="200">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Rolle*</label>
        <select name="rolle_id" class="form-control" required>
            <option value="">Bitte wählen...</option>
            <?php foreach($data['rollen'] as $r): ?>
                <option value="<?= htmlspecialchars($r['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="mb-3 form-check">
        <input type="checkbox" name="aktiv" class="form-check-input" id="aktiv" checked>
        <label class="form-check-label" for="aktiv">Aktiv</label>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Passwort* (mindestens 8 Zeichen)</label>
        <input type="password" name="password" class="form-control" required minlength="8">
    </div>
    
    <button type="submit" class="btn btn-success">Speichern</button>
    <a href="<?php echo BASE_URL; ?>/users" class="btn btn-secondary">Abbrechen</a>
</form>
