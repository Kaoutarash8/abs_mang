<?php
require_once __DIR__.'/../../core/Controller.php';
require_once __DIR__.'/../../models/UserModel.php';
    // Activation des erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class UsersController extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
    }

public function index() {
    $this->requireRole('admin');
    
    // Récupération des paramètres de filtrage
    $filters = [
        'search' => $_GET['search'] ?? '',
        'classe_id' => $_GET['classe_id'] ?? null
    ];
    
    $data = [
        'students' => $this->userModel->getStudentsWithClasses($filters),
        'teachers' => $this->userModel->getAllProfessors(),
        'admins' => $this->userModel->getUsersByRole('admin'),
        'classes' => $this->userModel->getAllClasses(),
        'filters' => $filters // Pour pré-remplir le formulaire
    ];
    
    if (isset($_GET['edit'])) {
        $userId = (int)$_GET['edit'];
        $data['editUser'] = $this->getUserForEdit($userId);
        $data['showEditForm'] = true;
    }

    $this->view('admin/users', $data);
}

    private function getUserForEdit($userId) {
        $user = $this->userModel->getUserById($userId);
        
        if ($user && $user->role === 'etudiant') {
            $studentData = $this->userModel-> getStudentsWithClasses($userId);
            $user->classe_id = $studentData->id_classe ?? null;
            $user->nom_classe = $studentData->nom_classe ?? null;
        }
        
        return $user;
    }
     

 public function store() {
    $this->requireRole('admin');
    $this->validateCsrfToken($_POST['csrf_token'] ?? '');

    try {
        // Génération email/mot de passe si vide
        if (empty($_POST['email'])) {
            $_POST['email'] = $this->generateEmail($_POST['firstName'], $_POST['lastName']);
        }
        if (empty($_POST['password'])) {
            $_POST['password'] = $this->generatePassword();
        }

        $this->validateUserData($_POST);
        $data = $this->prepareUserData($_POST);
        
        // Enregistrement avec gestion des tables spécifiques
        $userId = $this->userModel->createUserWithRole($data, $_POST['userType'], $_POST['classe'] ?? null);
        
        if ($userId) {
            $message = 'Utilisateur créé avec succès. ';
            $message .= 'Email: ' . $data['email'] . ' | ';
            $message .= 'Mot de passe: ' . $_POST['password'];
            $this->flash('success', $message);
        } else {
            throw new Exception("Erreur lors de l'enregistrement");
        }
    } catch (Exception $e) {
        $this->flash('error', $e->getMessage());
    }
    
    $this->redirect('/admin/users');
}


public function delete($id) {
   
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        die('Méthode non autorisée');
    }

   
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        http_response_code(403);
        die('Token CSRF invalide');
    }

 
    $type = $_POST['type'] ?? '';
    if (!in_array($type, ['etudiant', 'professeur'])) {
        http_response_code(400);
        die('Type d\'utilisateur invalide');
    }

    try {
    
        $success = $this->userModel->deleteUser($id, $type);
        
        if ($success) {
            $_SESSION['flash_success'] = 'Utilisateur supprimé avec succès';
        } else {
            $_SESSION['flash_error'] = 'Échec de la suppression';
        }
    } catch (Exception $e) {
        $_SESSION['flash_error'] = 'Erreur: ' . $e->getMessage();
    }

    
    header('Location: /gestion_absences22222/public/admin/users');
    exit;
}
   

private function validateUserData($postData, $excludeId = null) {
    $requiredFields = ['lastName', 'firstName', 'userType'];
    
    foreach ($requiredFields as $field) {
        if (empty($postData[$field])) {
            throw new Exception("Le champ " . ucfirst($field) . " est requis");
        }
    }

    // Validation spécifique pour les étudiants
    if ($postData['userType'] === 'etudiant' && empty($postData['classe'])) {
        throw new Exception("Une classe doit être sélectionnée pour les étudiants");
    }

    // Vérification email
    $email = $postData['email'] ?? $this->generateEmail($postData['firstName'], $postData['lastName']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Email invalide");
    }
    if ($this->userModel->emailExists($email, $excludeId)) {
        throw new Exception("Email déjà utilisé");
    }
}

    private function prepareUserData($postData, $id = null) {
        $data = [
            'nom' => htmlspecialchars(trim($postData['lastName'])),
            'prenom' => htmlspecialchars(trim($postData['firstName'])),
            'role' => $postData['userType']
        ];

        // Gestion de l'email
        $data['email'] = !empty($postData['email']) 
            ? filter_var(trim($postData['email']), FILTER_SANITIZE_EMAIL)
            : $this->generateEmail($postData['firstName'], $postData['lastName']);

        if ($id) {
            $data['id'] = $id;
        }

        // Gestion du mot de passe
        $data['mot_de_passe'] = !empty($postData['password'])
            ? password_hash($postData['password'], PASSWORD_DEFAULT)
            : password_hash($this->generatePassword(), PASSWORD_DEFAULT);

        return $data;
    }

 private function generateEmail($firstName, $lastName) {
    $base = strtolower($this->cleanString($firstName) . '.' . $this->cleanString($lastName));
    $email = $base . '@fa.com';
    $i = 1;
    while ($this->userModel->emailExists($email)) {
        $email = $base . $i . '@fa.com';
        $i++;
    }
    return $email;
}

    private function generatePassword() {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle($chars), 0, 10);
    }

    private function cleanString($string) {
        $string = preg_replace('/[^a-zA-Z\s]/', '', $string);
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        return preg_replace('/[^a-zA-Z]/', '', $string);
    }
}