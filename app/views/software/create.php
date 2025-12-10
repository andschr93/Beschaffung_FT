<h2>Neue Software anlegen</h2>
<?php if (!empty($data['error'])) { echo '<div class="alert alert-danger">'.htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8').'</div>'; } ?>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
    
    <div class="mb-3">
        <label class="form-label">Name*</label>
        <input type="text" name="name" class="form-control" required maxlength="200">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Beschreibung*</label>
        <textarea name="beschreibung" class="form-control" required maxlength="500" rows="3"></textarea>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Lizenztyp*</label>
        <select name="lizenztyp" class="form-control" required>
            <option value="">Bitte wählen...</option>
            <option value="Einzellizenz">Einzellizenz</option>
            <option value="Volumenlizenz">Volumenlizenz</option>
            <option value="Subscription">Subscription</option>
            <option value="Open Source">Open Source</option>
            <option value="Freeware">Freeware</option>
        </select>
    </div>
    
    <div class="mb-3 form-check">
        <input type="checkbox" name="ist_standard" class="form-check-input" id="ist_standard">
        <label class="form-check-label" for="ist_standard">Standard-Software</label>
    </div>
    
    <div class="mb-3 form-check">
        <input type="checkbox" name="aktiv" class="form-check-input" id="aktiv" checked>
        <label class="form-check-label" for="aktiv">Aktiv</label>
    </div>
    
    <button type="submit" class="btn btn-success">Speichern</button>
    <a href="<?php echo BASE_URL; ?>/software" class="btn btn-secondary">Abbrechen</a>
</form>
