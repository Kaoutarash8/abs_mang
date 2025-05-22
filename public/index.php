<?php
// public/index.php

// Chargement de la configuration
require_once __DIR__ . '/../app/config/config.php';

// Configuration du débogage
error_reporting(E_ALL);
ini_set('display_errors', DEBUG ? 1 : 0);

// Autoloader optimisé
spl_autoload_register(function ($className) {
    // Convertit les namespaces en chemin de fichier
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    
    $directories = [
        __DIR__.'/../app/core/',
        __DIR__.'/../app/controllers/',
        __DIR__.'/../app/models/',
        __DIR__.'/../app/helpers/'
    ];

    foreach ($directories as $dir) {
        $file = $dir.$className.'.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    if (DEBUG) {
        error_log("[AUTOLOAD] Classe non trouvée: {$className}");
        error_log("[AUTOLOAD] Chemins vérifiés: ".implode(', ', $directories));
    }
});


// Gestion des erreurs centralisée
set_exception_handler(function ($e) {
    http_response_code(500);
    if (DEBUG) {
        die("<h1>Erreur d'application</h1>
            <p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
            <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>");
    } else {
        die("Une erreur est survenue. Veuillez réessayer plus tard.");
    }
});

// Gestion de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Génération du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


// Lancement de l'application
try {
    new App();
} catch (Throwable $e) {
    if (DEBUG) {
        die("Erreur d'initialisation: " . htmlspecialchars($e->getMessage()));
    }
    die("Application indisponible");

}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);