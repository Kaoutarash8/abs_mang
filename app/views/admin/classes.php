<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des Classes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/gestion_absences22222/public/assets/css/admin/style.css">
    <link rel="stylesheet" href="/gestion_absences22222/public/assets/css/admin/classes.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Admin</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="/gestion_absences22222/public/admin/dashboard"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                <li><a href="/gestion_absences22222/public/admin/users"><i class="fas fa-chalkboard-teacher"></i> Gestion des Utilisateurs</a></li>
                <li class="active"><a href="/gestion_absences22222/public/admin/classes"><i class="fas fa-book"></i> Gestion des classes</a></li>
                <li><a href="/gestion_absences22222/public/admin/absences"><i class="fas fa-clipboard-list"></i> Gestion Absences</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="/gestion_absences22222/public/auth/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
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

            <!-- Content -->
            <div class="content">
                <h1 class="page-title">Gestion des Classes</h1>
                
                <!-- Add Class Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Ajouter une nouvelle classe</h3>
                    </div>
                    <div class="card-body">
                        <form action="/gestion_absences22222/public/admin/classes/add" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nom_classe" class="form-label">Nom de la classe</label>
                                        <input type="text" class="form-control" id="nom_classe" name="nom_classe" required>
                                    </div>
                                </div>
                                                 <!-- Changez cette partie -->
                   <div class="col-md-6">
                        <div class="mb-3">
                       <label for="nom_salle" class="form-label">Nom de la salle</label>
                          <input type="text" class="form-control" id="nom_salle" name="nom_salle" required>
                        </div>
                        </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Ajouter la classe</button>
                        </form>
                    </div>
                </div>

                <!-- Classes List -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Liste des classes</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Salle</th>
                                        <th>Étudiants</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($classes as $classe): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($classe['nom_classe']) ?></td>
                                        <td><?= htmlspecialchars($classe['salle_nom'] ?? 'Non attribuée') ?></td>
                                        <td><?= $classe['nombre_etudiants'] ?></td>
                                        
                                        
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Add Module to Class -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Ajouter un module à une classe</h3>
                    </div>
                    <div class="card-body">
                        <form action="/gestion_absences22222/public/admin/classes/addModuleToClass" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="module_class_id" class="form-label">Classe</label>
                                        <select class="form-select" id="module_class_id" name="id_classe" required>
                                            <option value="">Sélectionner une classe</option>
                                            <?php foreach ($classes as $classe): ?>
                                                <option value="<?= $classe['id'] ?>"><?= htmlspecialchars($classe['nom_classe']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nom_module" class="form-label">Nom du module</label>
                                        <input type="text" class="form-control" id="nom_module" name="nom_module" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Ajouter le module</button>
                        </form>
                    </div>
                </div>

                <!-- Assign Professor to Module -->
                <div class="card mb-4">
                    <div class="card-header">
     
    <h1>Assigner un professeur à un module</h1>

    <form action="/gestion_absences22222/public/admin/classes/assignprofessor" method="POST">
        <div class="form-group">
            <label for="id_classe">Classe :</label>
            <select name="id_classe" id="id_classe" class="form-control" required>
                <option value="">Sélectionner une classe</option>
                <?php foreach ($classes as $classe): ?>
                    <option value="<?= $classe['id'] ?>"><?= $classe['nom_classe'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_module">Module :</label>
            <select name="id_module" id="id_module" class="form-control" required>
                <option value="">Sélectionner un module</option>
                <?php foreach ($modules as $module): ?>
                    <option value="<?= $module['id'] ?>">
                        <?= $module['nom'] ?> (<?= $module['nom_classe'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_professeur">Professeur :</label>
            <select name="id_professeur" id="id_professeur" class="form-control" required>
                <option value="">Sélectionner un professeur</option>
                <?php foreach ($professeurs as $professeur): ?>
                    <option value="<?= $professeur['id'] ?>">
                        <?= $professeur['nom'] ?> <?= $professeur['prenom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="volume_horaire">Volume horaire (heures) :</label>
            <input type="number" name="volume_horaire" id="volume_horaire" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Assigner</button>
    </form>
</div>
                    </div>
                </div>

                <!-- Class Details Modals -->
                <div class="modal fade" id="studentsModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Étudiants de la classe</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="studentsList">
                                <!-- Students will be loaded here via AJAX -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modulesModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modules de la classe</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="modulesList">
                                <!-- Modules will be loaded here via AJAX -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="professorsModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Professeurs de la classe</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="professorsList">
                                <!-- Professors will be loaded here via AJAX -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
       
</body>
</html>