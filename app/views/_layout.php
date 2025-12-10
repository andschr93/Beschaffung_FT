<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Beschaffungs- & Onboarding-System für Stadtverwaltung">
    <title>Beschaffung & Onboarding | Stadtverwaltung</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Modern Design System -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/modern-design.css">
    
    <!-- Bootstrap Icons (for fallback) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    
    <div class="app-wrapper">
        
        <!-- ═══════════════════════════════════════════════════════════
             SIDEBAR NAVIGATION
             ═══════════════════════════════════════════════════════════ -->
        <aside class="app-sidebar">
            <!-- Logo -->
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">🏛️</div>
                <div class="sidebar-logo-text">Stadtverwaltung</div>
            </div>
            
            <!-- Navigation -->
            <nav>
                <ul class="sidebar-nav">
                    <?php
                    $currentPath = $_SERVER['REQUEST_URI'];
                    $basePath = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
                    $route = str_replace($basePath, '', $currentPath);
                    $route = strtok($route, '?'); // Remove query string
                    
                    // Navigation items based on role
                    $navItems = [
                        [
                            'path' => '/it-dashboard',
                            'icon' => '🖥️',
                            'label' => 'IT-Dashboard',
                            'roles' => [2, 5]
                        ],
                        [
                            'path' => '/admin',
                            'icon' => '⚡',
                            'label' => 'Admin-Dashboard',
                            'roles' => [1]
                        ],
                        [
                            'path' => '/mitarbeiter',
                            'icon' => '👥',
                            'label' => 'Mitarbeiter',
                            'roles' => [1, 2, 4, 5]
                        ],
                        [
                            'path' => '/hardware',
                            'icon' => '💻',
                            'label' => 'Hardware',
                            'roles' => [1, 2, 5]
                        ],
                        [
                            'path' => '/software',
                            'icon' => '📦',
                            'label' => 'Software',
                            'roles' => [1, 2, 5]
                        ],
                        [
                            'path' => '/users',
                            'icon' => '⚙️',
                            'label' => 'Benutzerverwaltung',
                            'roles' => [1, 3]
                        ]
                    ];
                    
                    $userRole = $_SESSION['user']['rolle_id'] ?? null;
                    
                    foreach ($navItems as $item):
                        // Check if user has permission
                        if (!in_array($userRole, $item['roles'])) continue;
                        
                        $isActive = ($route === $item['path']) ? 'active' : '';
                    ?>
                        <li class="nav-item">
                            <a href="<?= BASE_URL . $item['path'] ?>" class="nav-link <?= $isActive ?>">
                                <span class="nav-icon"><?= $item['icon'] ?></span>
                                <span><?= $item['label'] ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            
            <!-- User Section (Bottom) -->
            <?php if (isset($_SESSION['user'])): ?>
            <div class="sidebar-user">
                <div class="user-info">
                    <div class="user-avatar">
                        <?= strtoupper(substr($_SESSION['user']['vorname'], 0, 1)) ?>
                    </div>
                    <div class="user-details">
                        <div class="user-name">
                            <?= htmlspecialchars($_SESSION['user']['vorname'] . ' ' . $_SESSION['user']['nachname'], ENT_QUOTES, 'UTF-8') ?>
                        </div>
                        <div class="user-role">
                            <?php
                            $roleNames = [
                                1 => 'Administrator',
                                2 => 'IT-Abteilung',
                                3 => 'User Management',
                                4 => 'Vorzimmer',
                                5 => 'IT-Support'
                            ];
                            echo $roleNames[$_SESSION['user']['rolle_id']] ?? 'Benutzer';
                            ?>
                        </div>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/logout" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i> Abmelden
                </a>
            </div>
            <?php endif; ?>
        </aside>
        
        <!-- ═══════════════════════════════════════════════════════════
             MAIN CONTENT AREA
             ═══════════════════════════════════════════════════════════ -->
        <div class="app-main">
            <!-- Header -->
            <header class="app-header">
                <h1 class="header-title">
                    <?php
                    // Page title based on current route
                    $pageTitles = [
                        '/it-dashboard' => 'IT-Dashboard',
                        '/admin' => 'Admin-Dashboard',
                        '/mitarbeiter' => 'Mitarbeiter-Verwaltung',
                        '/hardware' => 'Hardware-Stammdaten',
                        '/software' => 'Software-Stammdaten',
                        '/users' => 'Benutzerverwaltung',
                        '/warenkorb' => 'Warenkorb',
                        '/' => 'Dashboard'
                    ];
                    
                    echo $pageTitles[$route] ?? 'Beschaffungssystem';
                    ?>
                </h1>
                
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span style="color: var(--gray-600); font-size: var(--text-sm);">
                        <?= date('d.m.Y') ?>
                    </span>
                </div>
            </header>
            
            <!-- Content -->
            <main class="app-content">
                <?php if (isset($content)) echo $content; ?>
            </main>
        </div>
        
    </div>
    
    <!-- Optional: Keep Bootstrap JS for existing components -->
    <script src="<?= BASE_URL ?>/bootstrap.bundle.min.js"></script>
    
</body>
</html>
