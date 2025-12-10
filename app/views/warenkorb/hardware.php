<!-- ═══════════════════════════════════════════════════════════ -->
<!-- HARDWARE-AUSWAHL FÜR WARENKORB -->
<!-- User kann Hardware für einen Mitarbeiter auswählen -->
<!-- ═══════════════════════════════════════════════════════════ -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>💻 Hardware auswählen</h1>
        <p class="text-muted">
            Für: <strong><?= htmlspecialchars($data['mitarbeiter']['vorname'] . ' ' . $data['mitarbeiter']['nachname'], ENT_QUOTES, 'UTF-8') ?></strong>
        </p>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>/warenkorb?mitarbeiter_id=<?= $data['mitarbeiter']['id'] ?>" class="btn btn-secondary">
            ← Zurück zum Warenkorb
        </a>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        ✓ Hardware wurde zum Warenkorb hinzugefügt!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        ⚠ Fehler beim Hinzufügen. Bitte versuchen Sie es erneut.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="alert alert-info">
    <strong>💡 Hinweis:</strong> Wählen Sie die Hardware aus, die dieser Mitarbeiter benötigt. 
    Standard-Hardware wird automatisch vorgeschlagen.
</div>

<!-- ═══ HARDWARE-LISTE ═══ -->
<div class="row g-3">
    <?php 
    // Bereits im Warenkorb IDs sammeln (für Highlighting)
    $bereits_hinzugefuegte_ids = array_column($data['bereits_im_warenkorb'], 'hardware_id');
    ?>
    
    <?php foreach ($data['hardware_liste'] as $hardware): ?>
        <?php 
        // Ist diese Hardware schon im Warenkorb?
        $bereits_hinzugefuegt = in_array($hardware['id'], $bereits_hinzugefuegte_ids);
        ?>
        
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 <?= $bereits_hinzugefuegt ? 'border-success' : '' ?>">
                <div class="card-body">
                    <!-- Kategorie-Badge -->
                    <div class="mb-2">
                        <span class="badge bg-secondary"><?= htmlspecialchars($hardware['kategorie'] ?? 'Sonstiges', ENT_QUOTES, 'UTF-8') ?></span>
                        <?php if ($hardware['ist_standard']): ?>
                            <span class="badge bg-success">⭐ Standard</span>
                        <?php endif; ?>
                        <?php if ($bereits_hinzugefuegt): ?>
                            <span class="badge bg-success">✓ Im Warenkorb</span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Hardware-Name -->
                    <h5 class="card-title"><?= htmlspecialchars($hardware['name'] ?? 'Unbenannt', ENT_QUOTES, 'UTF-8') ?></h5>
                    
                    <!-- Status -->
                    <p class="text-muted small mb-3">
                        Status: <?= $hardware['aktiv'] ? '✓ Aktiv' : '✗ Inaktiv' ?>
                    </p>
                    
                    <!-- Formular zum Hinzufügen -->
                    <form method="post" action="<?php echo BASE_URL; ?>/warenkorb/addHardware">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="mitarbeiter_id" value="<?= $data['mitarbeiter']['id'] ?>">
                        <input type="hidden" name="hardware_id" value="<?= $hardware['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label small">Anzahl:</label>
                            <input type="number" name="anzahl" class="form-control form-control-sm" 
                                   value="1" min="1" max="99" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small">Optionaler Hinweis:</label>
                            <input type="text" name="hinweis" class="form-control form-control-sm" 
                                   placeholder="z.B. 1TB statt 512GB">
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <?= $bereits_hinzugefuegt ? '+ Erneut hinzufügen' : '+ Zum Warenkorb' ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
    <?php if (count($data['hardware_liste']) === 0): ?>
        <div class="col-12">
            <div class="alert alert-warning">
                Keine Hardware-Artikel gefunden. 
                <a href="<?php echo BASE_URL; ?>/hardware/create">Jetzt Hardware anlegen »</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    
    .border-success {
        border-width: 2px !important;
    }
</style>

