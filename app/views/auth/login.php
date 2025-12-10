<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Beschaffung & Onboarding</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        
        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 2rem;
        }
        
        .login-card {
            background: #fff;
            border-radius: 1.5rem;
            padding: 3rem 2.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a1d29;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: #6c757d;
            font-size: 0.95rem;
        }
        
        .login-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border-radius: 0.75rem;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 0.75rem;
            padding: 0.875rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-danger {
            background: #fee;
            color: #c33;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.875rem;
            color: rgba(255,255,255,0.8);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    📋
                </div>
                <h1>Willkommen zurück</h1>
                <p>Beschaffungs- & Onboarding-System</p>
            </div>
            
            <?php if (!empty($data['error'])) { ?>
                <div class="alert alert-danger">
                    <strong>⚠️ Fehler:</strong><br>
                    <?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php } ?>
            
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="mb-3">
                    <label for="email" class="form-label">E-Mail-Adresse</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="ihre.email@firma.de" required autofocus>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Passwort</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-login w-100">
                    Anmelden
                </button>
            </form>
        </div>
        
        <div class="login-footer">
            <p>© 2024 Beschaffungs-System | IHK Prüfungsprojekt</p>
        </div>
    </div>
    
    <script src="<?php echo BASE_URL; ?>/bootstrap.bundle.min.js"></script>
</body>
</html>
