<?php
// Fichier: views/professor/absences_par_classe.php
// À inclure depuis votre contrôleur ProfesseurController
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Espace Professeur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="professor-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="../assets/images/logo.png" alt="Logo" class="logo">
                <h2>Professeur</h2>
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="/gestion_absences22222/app/views/prof"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                <li><a href="attendance.html"><i class="fas fa-clipboard-check"></i> Présences</a></li>
                <li><a href="requests.html"><i class="fas fa-envelope"></i> Demandes d'absence</a></li>
                <li><a href="students.html"><i class="fas fa-users"></i> Étudiants</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="#" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
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
                <div class="user-profile">
                    <img src="../assets/images/professor-avatar.jpg" alt="Professeur">
                    <span>Prof. Dupont</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </nav>

            <!-- Dashboard Content -->
           <div class="main-content">
    <h1>Bienvenue, <?= htmlspecialchars($professorName) ?></h1>
    
    <div class="card">
        <h2>Absences par classe</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Classe</th>
                    <th>Matière</th>
                    <th>Nombre d'absences</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($absencesParClasse as $absence): ?>
                <tr>
                    <td><?= htmlspecialchars($absence['nom_classe']) ?></td>
                    <td><?= htmlspecialchars($absence['nom_module']) ?></td>
                    <td><?= (int)$absence['nombre_absences'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/dashboard.js"></script>
</body>

</html>
