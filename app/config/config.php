<?php
// app/config/config.php

define('DEBUG', true);//Active le **mode débogage**. Quand `true`, cela permet d’afficher les erreurs pour faciliter le développement.


// Configuration de l'application
define('URL_ROOT', 'http://localhost/gestion_absences22222/public'); //Définit l’URL de base de l'application, utilisée pour générer dynamiquement les liens
                                                                    // (ex. liens vers les pages, fichiers CSS, etc.).

define('BASE_URL', '/gestion_absences22222'); //hemin de base sur le serveur.  
                                            //Utilisé pour construire les **chemins relatifs** dans le projet, souvent avec les assets (CSS, JS...).

define('SITE_NAME', 'Gestion des Absences');

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gestion_absences');
define('DB_CHARSET', 'utf8mb4');
/* Ces constantes sont utilisées pour se connecter à la base de données MySQL :

DB_HOST : Adresse du serveur MySQL 

DB_USER : Nom d’utilisateur MySQL.

DB_PASS : Mot de passe MySQL (souvent vide en local).

DB_NAME : Nom de la base utilisée.

DB_CHARSET : Encodage utilisé (ici UTF-8 multibyte pour mieux gérer les caractères spéciaux). */

// Configuration des sessions
define('SESSION_NAME', 'gabs_session');
define('SESSION_LIFETIME', 3600); // 1 heure

// Ne pas démarrer la session ici, elle est gérée dans index.php
/*
Le fichier config.php est un fichier de configuration qui stocke les constantes nécessaires au bon fonctionnement de l’application web.

Il permet de :

Centraliser les paramètres globaux comme :

L’URL de base du site,

Les informations de connexion à la base de données,

Le nom du site,

Les paramètres de session,

Le mode debug (pour afficher ou cacher les erreurs pendant le développement).

*/