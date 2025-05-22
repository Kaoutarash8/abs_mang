<?php 
// Solution ultra-simple qui fonctionne à coup sûr
$base = '/gestion_absences22222/public';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="<?=$base?>/assets/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-left animate__animated animate__fadeInLeft">
            <div class="login-brand">
                <img src="<?=$base?>/assets/images/logo.png" alt="Logo" class="logo animate__animated animate__bounceIn">
                <h1 class="animate__animated animate__fadeIn"></h1>
                <p class="animate__animated animate__fadeIn animate__delay-1s">Système de gestion des présences académiques</p>
            </div>
            <div class="login-features animate__animated animate__fadeInUp animate__delay-1s">
                <div class="feature">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Espace Professeurs</span>
                </div>
                <div class="feature">
                    <i class="fas fa-user-graduate"></i>
                    <span>Espace Étudiants</span>
                </div>
                <div class="feature">
                    <i class="fas fa-cogs"></i>
                    <span>Administration</span>
                </div>
            </div>
        </div>

        <div class="login-right animate__animated animate__fadeInRight">
            <div class="login-form-container">
                <div class="login-header">
                    <h2>Connexion</h2>
                    <p>Accédez à votre espace personnel</p>
                </div>

                <form action="<?=$base?>/auth/login" method="POST" id="loginForm" class="login-form">
                    <input type="hidden" name="csrf_token" value="<?= $data['csrf_token'] ?>">
                    
                    <?php if (isset($flash) && is_array($flash)): ?>
                        <div class="alert <?= htmlspecialchars($flash['class'] ?? 'alert-danger') ?> text-center" role="alert">
                            <?= htmlspecialchars($flash['message'] ?? '') ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="email">Identifiant</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" id="email" name="email" placeholder="Email" 
                                   value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>
                        </div>
                        <?php if (!empty($data['email_err'])): ?>
                            <span class="text-danger"><?= htmlspecialchars($data['email_err']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
                            <button type="button" class="password-toggle" aria-label="Afficher le mot de passe">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <?php if (!empty($data['password_err'])): ?>
                            <span class="text-danger"><?= htmlspecialchars($data['password_err']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-options">
                        <div class="form-check">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Se souvenir de moi</label>
                        </div>
                        <a href="#" id="forgotPassword" class="text-decoration-none">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="login-btn">
                        <span class="btn-text">Se connecter</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Mot de passe oublié -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?=$base?>/assets/js/login.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de l'affichage du mot de passe
        const togglePassword = () => {
            const passwordInput = document.getElementById("password");
            const icon = document.querySelector(".password-toggle i");
            const isPassword = passwordInput.type === "password";
            
            passwordInput.type = isPassword ? "text" : "password";
            icon.classList.toggle("fa-eye-slash", isPassword);
            icon.classList.toggle("fa-eye", !isPassword);
        };

        // Gestion du modal
        const setupForgotPassword = () => {
            const forgotPasswordBtn = document.getElementById("forgotPassword");
            if (forgotPasswordBtn) {
                forgotPasswordBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    new bootstrap.Modal(document.getElementById('forgotPasswordModal')).show();
                });
            }
        };

        // Initialisation
        document.querySelector(".password-toggle")?.addEventListener('click', togglePassword);
        setupForgotPassword();
    });
    </script>
</body>
</html>