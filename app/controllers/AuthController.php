<?php
require_once __DIR__.'/../core/Controller.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
       
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_secure' => isset($_SERVER['HTTPS']),
                'cookie_samesite' => 'Strict'
            ]);
        }
         

        parent::__construct(); 

        $this->userModel = $this->model('UserModel');
    }
     public function index() {
        $this->login(); // Redirige vers login par défaut
    }


    public function login() {
      

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Initialisation des données
            $data = [
                'email' => filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
                'csrf_token' => $_POST['csrf_token'] ?? ''
            ];

          
            if (!$this->validateCSRFToken($data['csrf_token'])) {
                die('Token CSRF invalide');
            }

          
            if (empty($data['email'])) {
                $data['email_err'] = 'Veuillez entrer votre email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Format d\'email invalide';
            }

            if (empty($data['password'])) {
                $data['password_err'] = 'Veuillez entrer votre mot de passe';
            }

        
            if (empty($data['email_err'])) {
                if (!$this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Aucun utilisateur trouvé avec cet email';
                }
            }

          
            if (empty($data['email_err']) && empty($data['password_err'])) {
                $loggedInUser = $this->userModel->secureLogin($data['email'], $data['password']);
                
                if ($loggedInUser) {
                    $this->createUserSession($loggedInUser);
                    
                  
                    switch($loggedInUser->role) {
                        case 'admin':
                            $this->redirect('admin/dashboard');
                            break;
                        case 'professeur':
                            $this->redirect('prof/dashboard');
                            break;
                        case 'etudiant':
                            $this->redirect('etudiant/dashboard');
                            break;
                        default:
                            $this->logout();
                            $this->redirect('auth/login');
                    }
                } else {
                    $data['password_err'] = 'Mot de passe incorrect';
                    $this->view('auth/login', $data);
                }
            } else {
                $this->view('auth/login', $data);
            }
        } else {
            // Si méthode GET, afficher le formulaire vide avec token CSRF
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
                'csrf_token' => $this->generateCSRFToken()
            ];
            $this->view('auth/login', $data);
        }
    }

    private function createUserSession($user) {
       
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->prenom.' '.$user->nom;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['last_activity'] = time();
    }

    public function logout() {
        

        $_SESSION = array();
        unset($_SESSION['csrf_token']);

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        $this->redirect('auth/login');
    }

    protected function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }
}