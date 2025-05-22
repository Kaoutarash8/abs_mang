<?php
// OPTION 2 (chemin absolu - recommandé)
require_once $_SERVER['DOCUMENT_ROOT'].'/gestion_absences22222/app/core/Controller.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/gestion_absences22222/app/models/Admin/AdminModel.php';
class DashboardController extends Controller
{
    private $adminModel;

    public function __construct()
    {
        parent::__construct();
        
        // Vérification de session
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                
                'cookie_samesite' => 'Strict'
            ]);
        }
        
        // Vérification du rôle
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('auth/login');
            exit;
        }

        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        $today = date('Y-m-d');
        
        $data = [
            'stats' => [
                'etudiants' => $this->adminModel->countEtudiants(),
                'professeurs' => $this->adminModel->countProfesseurs(),
                'absencesToday' => $this->adminModel->countAbsences(['date' => $today])
            ],
            'todayClasses' => $this->adminModel->getTodayClasses(),
            'recentAbsences' => $this->adminModel->getRecentAbsences(5),
            'absenceStats' => $this->getWeeklyAbsenceStats(),
            'csrf_token' => $this->generateCSRFToken()
        ];

        $this->view('admin/dashboard', $data);
    }

    private function getWeeklyAbsenceStats()
    {
        $stats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $stats[] = [
                'date' => $date,
                'count' => $this->adminModel->countAbsences(['date' => $date])
            ];
        }
        return $stats;
    }
}