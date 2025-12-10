<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">✏️ Mitarbeiter bearbeiten</h2>
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
                            <option value="Herr" <?= ($data['mitarbeiter']['anrede'] ?? '') === 'Herr' ? 'selected' : '' ?>>Herr</option>
                            <option value="Frau" <?= ($data['mitarbeiter']['anrede'] ?? '') === 'Frau' ? 'selected' : '' ?>>Frau</option>
                            <option value="Divers" <?= ($data['mitarbeiter']['anrede'] ?? '') === 'Divers' ? 'selected' : '' ?>>Divers</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Typ</label>
                        <input type="text" name="typ" class="form-control" 
                               value="<?= htmlspecialchars($data['mitarbeiter']['typ'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Vorname *</label>
                        <input type="text" name="vorname" class="form-control" required
                               value="<?= htmlspecialchars($data['mitarbeiter']['vorname'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Nachname *</label>
                        <input type="text" name="nachname" class="form-control" required
                               value="<?= htmlspecialchars($data['mitarbeiter']['nachname'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">E-Mail *</label>
                        <input type="email" name="email" class="form-control" required
                               value="<?= htmlspecialchars($data['mitarbeiter']['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Telefon</label>
                        <input type="text" name="telefon" class="form-control"
                               value="<?= htmlspecialchars($data['mitarbeiter']['telefon'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
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
                                <option value="<?= htmlspecialchars($b['id'], ENT_QUOTES, 'UTF-8') ?>"
                                        <?= ($data['mitarbeiter']['bereich_id'] ?? 0) == $b['id'] ? 'selected' : '' ?>>
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
                                        data-bid="<?= htmlspecialchars($a['bereich_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                        <?= ($data['mitarbeiter']['abteilung_id'] ?? 0) == $a['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($a['name'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Stellenbeschreibung</label>
                <input type="text" name="stellenbeschreibung" class="form-control"
                       value="<?= htmlspecialchars($data['mitarbeiter']['stellenbeschreibung'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Startdatum *</label>
                        <?php 
                        $startdatum = $data['mitarbeiter']['startdatum'] ?? '';
                        if ($startdatum instanceof DateTime) {
                            $startdatum = $startdatum->format('Y-m-d');
                        }
                        ?>
                        <input type="date" name="startdatum" class="form-control" required
                               value="<?= htmlspecialchars($startdatum, ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Priorität</label>
                        <select name="prioritaet" class="form-control">
                            <option value="Normal" <?= ($data['mitarbeiter']['prioritaet'] ?? '') === 'Normal' ? 'selected' : '' ?>>Normal</option>
                            <option value="Hoch" <?= ($data['mitarbeiter']['prioritaet'] ?? '') === 'Hoch' ? 'selected' : '' ?>>Hoch</option>
                            <option value="Sehr Hoch" <?= ($data['mitarbeiter']['prioritaet'] ?? '') === 'Sehr Hoch' ? 'selected' : '' ?>>Sehr Hoch</option>
                            <option value="Niedrig" <?= ($data['mitarbeiter']['prioritaet'] ?? '') === 'Niedrig' ? 'selected' : '' ?>>Niedrig</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="Offen" <?= ($data['mitarbeiter']['status'] ?? '') === 'Offen' ? 'selected' : '' ?>>Offen</option>
                    <option value="In Bearbeitung" <?= ($data['mitarbeiter']['status'] ?? '') === 'In Bearbeitung' ? 'selected' : '' ?>>In Bearbeitung</option>
                    <option value="Abgeschlossen" <?= ($data['mitarbeiter']['status'] ?? '') === 'Abgeschlossen' ? 'selected' : '' ?>>Abgeschlossen</option>
                    <option value="Abgebrochen" <?= ($data['mitarbeiter']['status'] ?? '') === 'Abgebrochen' ? 'selected' : '' ?>>Abgebrochen</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Besondere Hinweise</label>
                <textarea name="besondere_hinweise" class="form-control" rows="4"><?= htmlspecialchars($data['mitarbeiter']['besondere_hinweise'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
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
</div>
