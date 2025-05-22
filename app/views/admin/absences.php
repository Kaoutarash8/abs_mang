<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des Absences</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/gestion_absences22222/public/assets/css/admin/style.css">
    <link rel="stylesheet" href="/gestion_absences22222/public/assets/css/admin/absences.css">
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
                <li><a href="/gestion_absences22222/public/admin/classes"><i class="fas fa-book"></i> Gestion des classes</a></li>
                <li class="active"><a href="/gestion_absences22222/public/admin/absences"><i class="fas fa-clipboard-list"></i> Gestion Absences</a></li>
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
                <h1 class="page-title">Gestion des Absences</h1>
                
                <!-- Flash Messages -->
                <?php if (isset($_SESSION['flash'])): ?>
                    <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                        <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endforeach; ?>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>

                <!-- Filter Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Filtrer les absences</h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="/gestion_absences22222/public/admin/absences">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="class_id" class="form-label">Classe</label>
                                        <select class="form-select" id="class_id" name="class_id">
                                            <option value="">Toutes les classes</option>
                                            <?php foreach ($classes as $class): ?>
                                                <?php 
                                                    $classId = is_object($class) ? $class->id : $class['id'];
                                                    $className = is_object($class) ? $class->nom_classe : $class['nom_classe'];
                                                    $selected = isset($filters['class_id']) && $filters['class_id'] == $classId ? 'selected' : '';
                                                ?>
                                                <option value="<?= $classId ?>" <?= $selected ?>>
                                                    <?= htmlspecialchars($className) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="student_id" class="form-label">Étudiant</label>
                                        <select class="form-select" id="student_id" name="student_id">
                                            <option value="">Tous les étudiants</option>
                                            <?php foreach ($students as $student): ?>
                                                <?php
                                                    $studentId = is_object($student) ? $student->id : $student['id'];
                                                    $studentName = is_object($student) 
                                                        ? $student->nom . ' ' . $student->prenom 
                                                        : $student['nom'] . ' ' . $student['prenom'];
                                                    $selected = isset($filters['student_id']) && $filters['student_id'] == $studentId ? 'selected' : '';
                                                ?>
                                                <option value="<?= $studentId ?>" <?= $selected ?>>
                                                    <?= htmlspecialchars($studentName) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="date" name="date" value="<?= $filters['date'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="type" class="form-label">Type</label>
                                        <select class="form-select" id="type" name="type">
                                            <option value="">Tous types</option>
                                            <option value="justifiee" <?= isset($filters['type']) && $filters['type'] == 'justifiee' ? 'selected' : '' ?>>Justifiée</option>
                                            <option value="non_justifiee" <?= isset($filters['type']) && $filters['type'] == 'non_justifiee' ? 'selected' : '' ?>>Non justifiée</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Absences List -->
                <div class="card mb-4">
                  
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Étudiant</th>
                                        <th>Classe</th>
                                        <th>Module</th>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Type</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($absences)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Aucune absence trouvée</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($absences as $absence): ?>
                                            <?php
                                                $etudiantNom = is_object($absence) 
                                                    ? $absence->etudiant_nom . ' ' . $absence->etudiant_prenom 
                                                    : $absence['etudiant_nom'] . ' ' . $absence['etudiant_prenom'];
                                                $classeNom = is_object($absence) ? $absence->classe_nom : $absence['classe_nom'];
                                                $moduleNom = is_object($absence) ? $absence->module_nom : $absence['module_nom'];
                                                $dateSeance= is_object($absence) ? $absence->date_seance : $absence['date_seance'];
                                                $heureDebut = is_object($absence) ? $absence->heure_debut : $absence['heure_debut'];
                                                $heureFin = is_object($absence) ? $absence->heure_fin : $absence['heure_fin'];
                                                $typeAbsence = is_object($absence) ? $absence->type_absence : $absence['type_absence'];
                                                $absenceId = is_object($absence) ? $absence->id : $absence['id'];
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($etudiantNom) ?></td>
                                                <td><?= htmlspecialchars($classeNom) ?></td>
                                                <td><?= htmlspecialchars($moduleNom) ?></td>
                                                <td><?= date('d/m/Y', strtotime($dateSeance)) ?></td>
                                                <td><?= date('H:i', strtotime($heureDebut)) ?> - <?= date('H:i', strtotime($heureFin)) ?></td>
                                               <td>
    <span class="badge bg-<?= $typeAbsence === 'justifiée' ? 'success' : 'danger' ?>">
        <?= $typeAbsence === 'justifiée' ? 'Justifiée' : 'Non justifiée' ?>
    </span>
</td>
                                              
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Script pour gérer les interactions
        $(document).ready(function() {
            // Gestion des messages flash
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
            
            // Initialisation des tooltips
            $('[title]').tooltip();
            
            // Gestion du filtre étudiant en fonction de la classe sélectionnée
            $('#class_id').change(function() {
                const classId = $(this).val();
                if (classId) {
                    $('#student_id option').hide();
                    $('#student_id option[value=""]').show();
                    $('#student_id option[data-class="' + classId + '"]').show();
                    $('#student_id').val('');
                } else {
                    $('#student_id option').show();
                }
            });
        });
    </script>
</body>
</html>