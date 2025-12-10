<div style="text-align: center; padding: 3rem 0;">
  <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: #1a1d29;">
    <?= htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8') ?>
  </h1>
  <p style="font-size: 1.2rem; color: #6c757d; margin-bottom: 3rem;">
    Dieses System unterstützt das Onboarding und die Beschaffung intern.
  </p>
</div>

<div class="row g-4">
  <div class="col-md-6">
    <div class="card h-100 border-0 shadow-sm">
      <div class="card-body p-4">
        <h3 class="card-title mb-3">💻 Hardware-Verwaltung</h3>
        <p class="card-text text-muted">
          Verwalten Sie Hardware-Stammdaten, Kategorien und Standardausstattung für neue Mitarbeiter.
        </p>
        <a href="<?php echo BASE_URL; ?>/hardware" class="btn btn-primary">Zur Hardware</a>
      </div>
    </div>
  </div>
  
  <div class="col-md-6">
    <div class="card h-100 border-0 shadow-sm">
      <div class="card-body p-4">
        <h3 class="card-title mb-3">📦 Software-Verwaltung</h3>
        <p class="card-text text-muted">
          Verwalten Sie Software-Lizenzen, Lizenztypen und Standard-Softwarepakete.
        </p>
        <a href="<?php echo BASE_URL; ?>/software" class="btn btn-primary">Zur Software</a>
      </div>
    </div>
  </div>
  
  <div class="col-md-6">
    <div class="card h-100 border-0 shadow-sm">
      <div class="card-body p-4">
        <h3 class="card-title mb-3">👥 Mitarbeiter-Onboarding</h3>
        <p class="card-text text-muted">
          Verwalten Sie neue Mitarbeiter, deren Onboarding-Status und Beschaffungsprozesse.
        </p>
        <a href="<?php echo BASE_URL; ?>/mitarbeiter" class="btn btn-primary">Zu Mitarbeiter</a>
      </div>
    </div>
  </div>
  
  <div class="col-md-6">
    <div class="card h-100 border-0 shadow-sm">
      <div class="card-body p-4">
        <h3 class="card-title mb-3">⚙️ System-Verwaltung</h3>
        <p class="card-text text-muted">
          Verwalten Sie Benutzer, Rollen und Zugriffsrechte im System.
        </p>
        <a href="<?php echo BASE_URL; ?>/users" class="btn btn-primary">Zur Verwaltung</a>
      </div>
    </div>
  </div>
</div>

<style>
  .card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 1rem;
  }
  
  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
  }
  
  .card-title {
    font-size: 1.5rem;
    font-weight: 600;
  }
  
  .btn-primary {
    margin-top: 1rem;
  }
</style>
