<!-- Admin Dashboard - AdminLTE 4 Style -->

<div class="mb-8">
    <h2 style="font-size: var(--text-3xl); font-weight: var(--font-bold); color: #343a40; margin-bottom: var(--space-2);">
        Admin Dashboard
    </h2>
    <p style="color: #6c757d; font-size: var(--text-base);">
        Administrationsbereich
    </p>
</div>

<!-- Erfolgs-Meldung -->
<div class="alert alert-success" style="margin-bottom: var(--space-8);">
    <strong>✓ Erfolgreich angemeldet!</strong> Sie haben Admin-Rechte.
</div>

<!-- Info Boxes (AdminLTE 4 Style) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--space-6); margin-bottom: var(--space-8);">
    
    <!-- Box 1: Angemeldeter Benutzer (Blau) -->
    <div class="stat-card">
        <div class="stat-icon">👤</div>
        <div style="margin-bottom: var(--space-3);">
            <h3 style="font-size: var(--text-lg); font-weight: var(--font-semibold); color: #495057; margin-bottom: var(--space-2);">
                Angemeldeter Benutzer
            </h3>
            <div style="font-size: var(--text-2xl); font-weight: var(--font-bold); color: #343a40; margin-bottom: var(--space-2);">
                <?= htmlspecialchars($data['user']['vorname'] . ' ' . $data['user']['nachname'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <small style="color: #6c757d;">
                ID: <?= htmlspecialchars($data['user']['id'], ENT_QUOTES, 'UTF-8') ?> | 
                Rolle: Admin
            </small>
        </div>
    </div>
    
    <!-- Box 2: Sicherheit (Grün) -->
    <div class="stat-card success">
        <div class="stat-icon">🔒</div>
        <div>
            <h3 style="font-size: var(--text-lg); font-weight: var(--font-semibold); color: #495057; margin-bottom: var(--space-3);">
                Sicherheit
            </h3>
            <div style="display: flex; flex-direction: column; gap: var(--space-2);">
                <span class="badge badge-success">CSRF AKTIV</span>
                <span class="badge badge-success">XSS-SCHUTZ</span>
                <span class="badge badge-success">RATE-LIMIT</span>
            </div>
        </div>
    </div>
    
    <!-- Box 3: System-Status (Türkis) -->
    <div class="stat-card info">
        <div class="stat-icon">📊</div>
        <div>
            <h3 style="font-size: var(--text-lg); font-weight: var(--font-semibold); color: #495057; margin-bottom: var(--space-2);">
                System-Status
            </h3>
            <div style="font-size: var(--text-2xl); font-weight: var(--font-bold); color: #343a40; margin-bottom: var(--space-2);">
                <span class="badge badge-success">ONLINE</span>
            </div>
            <small style="color: #6c757d;">
                PHP <?= phpversion() ?> | <?= date('d.m.Y H:i') ?>
            </small>
        </div>
    </div>
    
</div>

<!-- Admin-Funktionen -->
<div style="margin-bottom: var(--space-6);">
    <h3 style="font-size: var(--text-2xl); font-weight: var(--font-bold); color: #343a40; margin-bottom: var(--space-6);">
        ⚡ Admin-Funktionen
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-6);">
        
        <!-- Benutzer -->
        <a href="<?= BASE_URL ?>/users" 
           class="card" 
           style="text-decoration: none; text-align: center; cursor: pointer; border-top: 3px solid #007bff;"
           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 0 1px rgba(0,0,0,.125), 0 8px 16px rgba(0,0,0,.2)';"
           onmouseout="this.style.transform=''; this.style.boxShadow='0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2)';">
            <div style="font-size: 4rem; margin-bottom: var(--space-3); opacity: 0.2;">👥</div>
            <h4 style="font-size: var(--text-xl); font-weight: var(--font-bold); color: #495057; margin-bottom: var(--space-2);">
                Benutzer
            </h4>
            <p style="font-size: var(--text-sm); color: #6c757d);">
                Verwaltung
            </p>
        </a>
        
        <!-- Hardware -->
        <a href="<?= BASE_URL ?>/hardware" 
           class="card" 
           style="text-decoration: none; text-align: center; cursor: pointer; border-top: 3px solid #dc3545;"
           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 0 1px rgba(0,0,0,.125), 0 8px 16px rgba(0,0,0,.2)';"
           onmouseout="this.style.transform=''; this.style.boxShadow='0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2)';">
            <div style="font-size: 4rem; margin-bottom: var(--space-3); opacity: 0.2;">💻</div>
            <h4 style="font-size: var(--text-xl); font-weight: var(--font-bold); color: #495057; margin-bottom: var(--space-2);">
                Hardware
            </h4>
            <p style="font-size: var(--text-sm); color: #6c757d;">
                Stammdaten
            </p>
        </a>
        
        <!-- Software -->
        <a href="<?= BASE_URL ?>/software" 
           class="card" 
           style="text-decoration: none; text-align: center; cursor: pointer; border-top: 3px solid #28a745;"
           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 0 1px rgba(0,0,0,.125), 0 8px 16px rgba(0,0,0,.2)';"
           onmouseout="this.style.transform=''; this.style.boxShadow='0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2)';">
            <div style="font-size: 4rem; margin-bottom: var(--space-3); opacity: 0.2;">📦</div>
            <h4 style="font-size: var(--text-xl); font-weight: var(--font-bold); color: #495057; margin-bottom: var(--space-2);">
                Software
            </h4>
            <p style="font-size: var(--text-sm); color: #6c757d;">
                Stammdaten
            </p>
        </a>
        
        <!-- Mitarbeiter -->
        <a href="<?= BASE_URL ?>/mitarbeiter" 
           class="card" 
           style="text-decoration: none; text-align: center; cursor: pointer; border-top: 3px solid #ffc107;"
           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 0 1px rgba(0,0,0,.125), 0 8px 16px rgba(0,0,0,.2)';"
           onmouseout="this.style.transform=''; this.style.boxShadow='0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2)';">
            <div style="font-size: 4rem; margin-bottom: var(--space-3); opacity: 0.2;">🆕</div>
            <h4 style="font-size: var(--text-xl); font-weight: var(--font-bold); color: #495057; margin-bottom: var(--space-2);">
                Mitarbeiter
            </h4>
            <p style="font-size: var(--text-sm); color: #6c757d;">
                Onboarding
            </p>
        </a>
        
    </div>
</div>
