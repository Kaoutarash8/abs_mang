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

---

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
