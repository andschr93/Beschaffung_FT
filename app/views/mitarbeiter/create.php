<div class="card" style="box-shadow: var(--shadow-md);">
    <div class="card-header">
        <h2 class="card-title">👤 Neuen Mitarbeiter anlegen</h2>
    </div>
    
    <?php if (!empty($data['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
    
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Anrede</label>
                    <select name="anrede" class="form-control">
                        <option value="">Bitte wählen...</option>
                        <option value="Herr">Herr</option>
                        <option value="Frau">Frau</option>
                        <option value="Divers">Divers</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Typ</label>
                    <select name="typ" class="form-control">
                        <option value="">Bitte wählen...</option>
                        <option value="Festanstellung">Festanstellung</option>
                        <option value="Zeitarbeit">Zeitarbeit</option>
                        <option value="Praktikum">Praktikum</option>
                        <option value="Ausbildung">Ausbildung</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Vorname *</label>
                    <input type="text" name="vorname" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Nachname *</label>
                    <input type="text" name="nachname" class="form-control" required>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">E-Mail *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Telefon</label>
                    <input type="text" name="telefon" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Bereich *</label>
                    <select name="bereich_id" class="form-control" required>
                        <option value="">Bitte wählen...</option>
                        <?php foreach ($data['bereiche'] as $b): ?>
                            <option value="<?= htmlspecialchars($b['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Abteilung *</label>
                    <select name="abteilung_id" class="form-control" required>
                        <option value="">Bitte wählen...</option>
                        <?php foreach ($data['abteilungen'] as $a): ?>
                            <option value="<?= htmlspecialchars($a['id'], ENT_QUOTES, 'UTF-8') ?>" 
                                    data-bid="<?= htmlspecialchars($a['bereich_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($a['name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Stellenbeschreibung</label>
            <input type="text" name="stellenbeschreibung" class="form-control">
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Startdatum *</label>
                    <input type="date" name="startdatum" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Priorität</label>
                    <select name="prioritaet" class="form-control">
                        <option value="Normal">Normal</option>
                        <option value="Hoch">Hoch</option>
                        <option value="Sehr Hoch">Sehr Hoch</option>
                        <option value="Niedrig">Niedrig</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="Offen">Offen</option>
                        <option value="In Bearbeitung">In Bearbeitung</option>
                        <option value="Abgeschlossen">Abgeschlossen</option>
                        <option value="Abgebrochen">Abgebrochen</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Besondere Hinweise</label>
            <textarea name="besondere_hinweise" class="form-control" rows="3"></textarea>
        </div>
        
        <div style="display: flex; gap: var(--space-3); margin-top: var(--space-6);">
            <button type="submit" class="btn btn-primary">
                💾 Speichern
            </button>
            <a href="<?= BASE_URL ?>/mitarbeiter" class="btn btn-secondary">
                ↩️ Abbrechen
            </a>
        </div>
    </form>
</div>
