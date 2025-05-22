 Application Web PHP MVC

Bienvenue dans ce projet PHP développé sans framework, reposant sur une architecture **MVC (Modèle - Vue - Contrôleur)** conçue pour être claire, modulaire, sécurisée et facilement maintenable.

 Objectifs du projet

- Comprendre et implémenter une architecture MVC en PHP natif
- Construire une application modulaire et maintenable
- Manipuler les sessions, la sécurité CSRF, et la gestion des erreurs
- Offrir une base de démarrage propre pour des projets web plus complexes

 Fonctionnalités principales

 Architecture MVC en PHP pur  
 Système de routage automatique via la classe `App`  
 Chargement dynamique des classes via un autoloader  
 Gestion des erreurs globalisée avec `set_exception_handler`  
 Génération automatique de **jetons CSRF** pour sécuriser les formulaires  
 Système de **flash messages** avec disparition automatique (jQuery)  
 **Filtrage dynamique** des étudiants selon la classe sélectionnée (jQuery)  
 Initialisation automatique des tooltips pour l'accessibilité  
 Design extensible avec Bootstrap 

---------------------------------------------------------------------------------------------
*Tableau de Bord Administratif
Affichage des statistiques clés (nombre d'étudiants, professeurs, absences du jour)

Accès rapide aux actions principales

*Gestion des Utilisateurs
Gestion des Étudiants:

Ajout/suppression d'étudiants

Attribution à des classes

Génération automatique d'emails et mots de passe

Gestion des Professeurs:

Création/suppression de comptes

Assignation aux modules

Interface avec onglets pour une navigation intuitive

*Gestion des Classes
Création et gestion des classes (nom, salle)

Ajout de modules aux classes

Assignation des professeurs aux modules avec volume horaire

Affichage du nombre d'étudiants par classe

* Gestion des Absences
Système de suivi complet des absences:

Filtrage 

Visualisation 

Affichage des détails 

Interface responsive avec tableau des absences
--------------------------------------------------------------------------------------------

Structure du projet


gestion_absences22222/
├── .htaccess                   
├── app/                        
│   ├── config/                
│   │   ├── config.php
│   │   └── database.php
│   ├── controllers/          
│   │   ├── AuthController.php
│   │   ├── Admin/
│   │   │   ├── AbsencesController.php
│   │   │   ├── ClassesController.php
│   │   │   └── DashboardController.php
│   │   ├── Etudiant/
│   │   └── Prof/
│   ├── core/                  
│   ├── models/                 
│   └── views/                  
├── public/                     
│   ├── css/                    
│   ├── js/                                
│   └── index.php               
├── scripts/                   
└── README.md  

*Technologies utilisées
_PHP (POO, MVC)

_MySQL

_HTML / CSS / JS

_Apache (via XAMPP)


