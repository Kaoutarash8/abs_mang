<?php

class App {
    protected $controller = 'AuthController';
    protected $method = 'login';
    protected $params = [];
    protected $basePath = '/gestion_absences22222';

    public function __construct() {
        try {
            $url = $this->parseUrl();

            // Gestion des routes admin
            if (isset($url[0]) && $url[0] === 'admin') {
                $controllerName = ucfirst($url[1] ?? 'dashboard') . 'Controller';
                $controllerFile = '../app/controllers/Admin/' . $controllerName . '.php';
                
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $this->controller = new $controllerName();
                    
                    // Gestion des méthodes
                    $this->method = $this->determineMethod($url);
                    
                    unset($url[0], $url[1]);
                } else {
                    throw new Exception("Contrôleur admin {$controllerFile} introuvable");
                }
            }
           
            elseif (isset($url[0]) && $url[0] === 'auth') {
                $this->controller = 'AuthController';
                $controllerFile = '../app/controllers/' . $this->controller . '.php';
                
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $this->controller = new $this->controller();
                    $this->method = $url[1] ?? 'login';
                    unset($url[0], $url[1]);
                }
            }
          
            else {
                if (!empty($url[0])) {
                    $controllerName = ucfirst($url[0]);
                    $controllerFile = '../app/controllers/' . $controllerName . 'Controller.php';

                    if (file_exists($controllerFile)) {
                        require_once $controllerFile;
                       $controllerClass = $controllerName . 'Controller';
                       $this->controller = new $controllerClass();

                        $this->method = $url[1] ?? 'index';
                        unset($url[0], $url[1]);
                    } else {
                        throw new Exception("Fichier contrôleur {$controllerFile} introuvable");
                    }
                } else {
                   
                    require_once '../app/controllers/AuthController.php';
                    $this->controller = new AuthController();
                }
            }

           
            $this->params = $url ? array_values($url) : [];

        
            call_user_func_array([$this->controller, $this->method], $this->params);

        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    protected function determineMethod($url) {
       
        $restMethods = [
            'add' => 'store',
            'edit' => 'edit',
            'update' => 'update',
            'delete' => 'delete'
        ];

       
        if (isset($url[2])) {
           
            if (array_key_exists($url[2], $restMethods)) {
                return $restMethods[$url[2]];
            }
            return $url[2];
        }

    
        return ($_SERVER['REQUEST_METHOD'] === 'POST') ? 'store' : 'index';
    }

    protected function parseUrl() {
    if (isset($_GET['url'])) {
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);

        
        if ($this->basePath && str_starts_with($url, trim($this->basePath, '/'))) {
            $url = substr($url, strlen(trim($this->basePath, '/')));
            $url = ltrim($url, '/');
        }

        return explode('/', $url);
    }
    return [];
}


    protected function handleError($error) {
        http_response_code(500);
        echo "<h1>Erreur d'application</h1>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($error->getMessage()) . "</p>";

        if (defined('DEBUG') && DEBUG) {
            echo "<pre>";
            var_dump([
                'Controller' => $this->controller,
                'Method' => $this->method,
                'Params' => $this->params,
                'Trace' => $error->getTraceAsString()
            ]);
            echo "</pre>";
        }

        exit;
    }
}