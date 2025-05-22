<?php
// Assurez-vous que ces variables sont bien définies avant de les utiliser
$students = $students ?? [];
$teachers = $teachers ?? [];
$classes = $classes ?? [];
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <title>Admin - Gestion des Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/gestion_absences22222/public/assets/css/admin/style.css">
    <style>
        
    </style>
</head>
<body>
    <!-- Container pour les alertes -->
    <div class="alert-container" id="alerts-container"></div>

    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header text-center py-3">
                <h2>Administrateur</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="/gestion_absences22222/public/admin/dashboard"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                <li><a href="/gestion_absences22222/public/admin/users"><i class="fas fa-chalkboard-teacher"></i> Gestion des Utilisateurs</a></li>
                <li><a href="/gestion_absences22222/public/admin/classes"><i class="fas fa-book"></i> Gestion des classes</a></li>
                <li class="active"><a href="/gestion_absences22222/public/admin/absences"><i class="fas fa-clipboard-list"></i> Gestion Absences</a></li>
            </ul>
          <div class="sidebar-footer">
                <a href="#" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>

        <!-- Main Content -->

        <div class="content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="page-title">
                        <i class="fas fa-users me-2"></i>Gestion des Utilisateurs
                    </h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-2"></i>Ajouter un utilisateur
                    </button>
                </div>
                <!-- Formulaire de filtrage -->
<div class="card mb-4">
    <div class="card-body">
        <form method="get" action="/gestion_absences22222/public/admin/users" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Recherche</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="<?= htmlspecialchars($filters['search'] ?? '') ?>" 
                       placeholder="Nom, prénom ou classe">
            </div>
            <div class="col-md-4">
                <label for="classe_id" class="form-label">Classe</label>
                <select class="form-select" id="classe_id" name="classe_id">
                    <option value="">Toutes les classes</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class->id ?>" 
                            <?= ($class->id == ($filters['classe_id'] ?? '')) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($class->nom_classe) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter me-2"></i>Filtrer
                </button>
               
            </div>
        </form>
    </div>
</div>
                <!-- User Tabs -->
                <ul class="nav nav-tabs" id="userTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">
                            <i class="fas fa-user-graduate me-2"></i>Étudiants (<?= count($students) ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="teachers-tab" data-bs-toggle="tab" data-bs-target="#teachers" type="button" role="tab">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Professeurs (<?= count($teachers) ?>)
                        </button>
                 
                </ul>

                <!-- Tab Content -->
                <div class="tab-content p-3 border border-top-0 rounded-bottom bg-white">
                    <!-- Students Tab -->
                    <div class="tab-pane fade show active" id="students" role="tabpanel">
                        <?php if (empty($students)): ?>
                            <div class="alert alert-info">Aucun étudiant enregistré</div>
                        <?php else: ?>
                            <div class="table-responsive">
                               <table class="table table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Classe</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $student): ?>
        <tr>
            <td><?= htmlspecialchars($student->id) ?></td>
            <td><?= htmlspecialchars($student->nom) ?></td>
            <td><?= htmlspecialchars($student->prenom) ?></td>
            <td><?= htmlspecialchars($student->email) ?></td>
            <td><?= htmlspecialchars($student->nom_classe ?? 'Non assigné') ?></td>
            <td>
    <form method="POST" action="/gestion_absences22222/public/admin/users/delete/<?= $student->id ?>">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="type" value="etudiant">
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
                          
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Teachers Tab -->
<div class="tab-pane fade" id="teachers" role="tabpanel">
    <?php if (empty($teachers)): ?>
        <div class="alert alert-info">Aucun professeur enregistré</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teachers as $teacher): ?>
                    <tr>
                        <td><?= htmlspecialchars($teacher->id) ?></td>
                        <td><?= htmlspecialchars($teacher->nom) ?></td>
                        <td><?= htmlspecialchars($teacher->prenom) ?></td>
                        <td><?= htmlspecialchars($teacher->email) ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <form method="POST" action="/gestion_absences22222/public/admin/users/delete/<?= $teacher->id ?>" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="type" value="professeur">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce professeur ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

    <!--Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Ajouter un utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="/gestion_absences22222/public/admin/users/store">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type d'utilisateur <span class="text-danger">*</span></label>
                            <select class="form-select" name="userType" id="userTypeSelect" required>
                                <option value="">Sélectionner...</option>
                                <option value="etudiant">Étudiant</option>
                                <option value="professeur">Professeur</option>
                                <option value="admin">Administrateur</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="classeField" style="display: none;">
                            <label class="form-label">Classe <span class="text-danger">*</span></label>
                            <select class="form-select" name="classe" id="classeSelect">
                                <option value="">Sélectionner une classe</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class->id ?>"><?= htmlspecialchars($class->nom_classe) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="lastName" id="lastNameInput" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="firstName" id="firstNameInput" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" id="emailInput" required readonly>
                            <button class="btn btn-outline-secondary" type="button" id="generateEmailBtn">
                                <i class="fas fa-sync-alt"></i> Générer
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="password" id="passwordInput" required readonly>
                            <button class="btn btn-outline-secondary" type="button" id="generatePasswordBtn">
                                <i class="fas fa-sync-alt"></i> Générer
                            </button>
                        </div>
                        <small class="text-muted">Le mot de passe sera affiché une seule fois</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'affichage du champ classe
    const userTypeSelect = document.getElementById('userTypeSelect');
    const classeField = document.getElementById('classeField');
    
    userTypeSelect.addEventListener('change', function() {
        classeField.style.display = this.value === 'etudiant' ? 'block' : 'none';
        if (this.value !== 'etudiant') {
            document.getElementById('classeSelect').value = '';
        }
    });
    
    // Génération d'email
    const generateEmailBtn = document.getElementById('generateEmailBtn');
    const emailInput = document.getElementById('emailInput');
    const firstNameInput = document.getElementById('firstNameInput');
    const lastNameInput = document.getElementById('lastNameInput');
    
    generateEmailBtn.addEventListener('click', function() {
        const firstName = firstNameInput.value.trim().toLowerCase();
        const lastName = lastNameInput.value.trim().toLowerCase();
        
        if (firstName && lastName) {
            // Supprime les accents et caractères spéciaux
            const cleanFirstName = firstName.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^a-z]/g, '');
            const cleanLastName = lastName.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^a-z]/g, '');
            
            emailInput.value = cleanFirstName + '.' + cleanLastName + '@fa.com';
        } else {
            alert('Veuillez d\'abord saisir le nom et le prénom');
        }
    });
    
    // Génération de mot de passe
    const generatePasswordBtn = document.getElementById('generatePasswordBtn');
    const passwordInput = document.getElementById('passwordInput');
    
    generatePasswordBtn.addEventListener('click', function() {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let password = '';
        for (let i = 0; i < 10; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        passwordInput.value = password;
    });
});
</script>

    <!-- JavaScript minimal pour la gestion des modals -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
</body>
</html>