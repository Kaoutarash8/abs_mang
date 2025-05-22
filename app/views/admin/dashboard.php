



<?php
require_once __DIR__.'/../../models/Admin/AdminModel.php';
$adminModel = new AdminModel();

// Récupération des données
$studentCount = $adminModel->countEtudiants();
$teacherCount = $adminModel->countProfesseurs();
$absenceCount = $adminModel->countAbsences(['date' => date('Y-m-d')]);
$todayClasses = count($adminModel->getTodayClasses());
$recentAbsences = $adminModel->getRecentAbsences(5);

// Préparation des données pour le graphique
$weekDays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
$weeklyStats = $adminModel->getWeeklyAbsenceStats();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/gestion_absences22222/public/assets/css/admin/style.css">
    <link rel="stylesheet" href="/gestion_absences22222/public/assets/css/admin/dashboard.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Admin</h2>
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="/gestion_absences22222/public/admin/dashboard"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                <li><a href="/gestion_absences22222/public/admin/users"><i class="fas fa-chalkboard-teacher"></i> Gestion des Utilisateurrs </a></li>
                <li><a href="/gestion_absences22222/public/admin/absences"><i class="fas fa-clipboard-list"></i> Gestion Absences</a></li>
                <li><a href="/gestion_absences22222/public/admin/classes"><i class="fas fa-book"></i> Gestion des classes </a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="/gestion_absences/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <nav class="top-nav">
                <div class="search-box">
                    <input type="text" placeholder="Rechercher...">
                    <button><i class="fas fa-search"></i></button>
                </div>
               
            </nav>

            <!-- Dashboard Content -->
            <div class="content">
                <h1 class="page-title">Tableau de bord</h1>
                
                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <h5 class="stat-title">Étudiants</h5>
                                <p class="stat-value"><?= $studentCount ?></p>
                                <p class="stat-change">Total inscrits</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <h5 class="stat-title">Professeurs</h5>
                                <p class="stat-value"><?= $teacherCount ?></p>
                                <p class="stat-change">Total enseignants</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <h5 class="stat-title">Absences</h5>
                                <p class="stat-value"><?= $absenceCount ?></p>
                                <p class="stat-change">Aujourd'hui</p>
                            </div>
                        </div>
                    </div>
            

                <!-- Main Dashboard Content -->
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <!-- Absence Chart -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3>Statistiques des absences</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="absenceChart"></canvas>
                            </div>
                        </div>

                        <!-- Recent Absences -->
                        
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <!-- Quick Actions -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3>Actions rapides</h3>
                            </div>
                            <div class="card-body">
                                <div class="quick-actions">
                                    <a href="/gestion_absences22222/public/admin/users" class="action-btn">
                                        <div class="action-icon bg-primary">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <span>Ajouter un professeur</span>
                                    </a>
                                    <a href="/gestion_absences22222/public/admin/users" class="action-btn">
                                        <div class="action-icon bg-success">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <span>Ajouter un étudiant</span>
                                    </a>
                                    <a href="/gestion_absences22222/public/admin/classes" class="action-btn">
                                        <div class="action-icon bg-info">
                                            <i class="fas fa-calendar-plus"></i>
                                        </div>
                                        <span>Ahouter une classe </span>
                                    </a>
                                    <a href="/gestion_absences22222/public/admin/classes" class="action-btn">
                                        <div class="action-icon bg-warning">
                                            <i class="fas fa-book-medical"></i>
                                        </div>
                                        <span>Ajouter un module</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Today's Schedule -->
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/gestion_absences/public/assets/js/admin/scripts.js"></script>
     <script src="/gestion_absences/public/assets/js/admin/dashboard.js"></script>
    
</body>
</html>