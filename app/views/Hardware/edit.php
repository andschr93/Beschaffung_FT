<h2>Hardware bearbeiten</h2>

<?php if (!empty($data['error'])) { echo '<div class="alert alert-danger">'.htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8').'</div>'; } ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
    
    <div class="mb-3">
        <label class="form-label">Kategorie*</label>
        <input type="text" name="kategorie" class="form-control" required maxlength="100" value="<?= htmlspecialchars($data['hardware']['kategorie'], ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Name*</label>
        <input type="text" name="name" class="form-control" required maxlength="200" value="<?= htmlspecialchars($data['hardware']['name'], ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" name="ist_standard" class="form-check-input" id="ist_standard" <?= $data['hardware']['ist_standard'] ? 'checked' : '' ?>>
        <label class="form-check-label" for="ist_standard">Standard-Hardware</label>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" name="aktiv" class="form-check-input" id="aktiv" <?= $data['hardware']['aktiv'] ? 'checked' : '' ?>>
        <label class="form-check-label" for="aktiv">Aktiv</label>
    </div>

    <button type="submit" class="btn btn-success">Speichern</button>
    <a href="<?php echo BASE_URL; ?>/hardware" class="btn btn-secondary">Abbrechen</a>
</form>

