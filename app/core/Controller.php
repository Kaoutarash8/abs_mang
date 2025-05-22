<?php
class Controller {
     public function __construct() {
       
    }
    public function view($view, $data = []) {
        $viewFile = '../app/views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            die('Vue non trouvée: ' . $view);
        }

        extract($data);
        require_once $viewFile;
    }

    public function model($model) {
        $modelFile = '../app/models/' . $model . '.php';
        
        if (!file_exists($modelFile)) {
            die('Modèle non trouvé: ' . $model);
        }

        require_once $modelFile;
        
        if (!class_exists($model)) {
            die('Classe non trouvée: ' . $model);
        }

        return new $model();
    }

    public function redirect($url, $statusCode = 302) {
        $url = trim($url, '/');
        $fullUrl = URL_ROOT . '/' . $url;
        
        if (headers_sent()) {
            die('Redirection impossible');
        }

        header('Location: ' . $fullUrl, true, $statusCode);
        exit();
    }

    public function flash($name, $message = '', $class = 'alert alert-success') {
        if (!empty($message)) {
            $_SESSION[$name] = [
                'message' => $message,
                'class' => $class
            ];
        } elseif (isset($_SESSION[$name])) {
            $flash = $_SESSION[$name];
            unset($_SESSION[$name]);
            return $flash;
        }
        return null;
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

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }
    
    protected function requireRole($role) {
        $this->requireLogin();
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
            $this->redirect('auth/login');
        }
    }
    
    protected function checkRole($role) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }
}