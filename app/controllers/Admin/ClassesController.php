<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/Admin/ClassModel.php';
require_once __DIR__ . '/../../models/Admin/AdminModel.php';

class ClassesController extends Controller {
    private $classModel;
    private $adminModel;

    public function __construct() {
        $this->classModel = new ClassModel();
       
    }

    public function index() {
      $classes = $this->classModel->getAllClassesWithDetails();
    $salles = $this->classModel->getAllSalles();  // Now using ClassModel
    $professeurs = $this->classModel->getAllProfesseurs();  // Now using ClassModel
    $modules = $this->classModel->getAllModules();

        $data = [
            'classes' => $classes,
            'salles' => $salles,
            'professeurs' => $professeurs,
            'modules' => $modules
        ];

        $this->view('admin/classes', $data);
    }
    
public function store() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $salle = $this->classModel->getSalleByName(trim($_POST['nom_salle']));
        
        if (!$salle) {
           
            $id_salle = $this->classModel->addSalle(trim($_POST['nom_salle']));
        } else {
            $id_salle = $salle['id'];
        }

        $data = [
            'nom_classe' => trim($_POST['nom_classe']),
            'id_salle' => $id_salle
        ];

        if ($this->classModel->addClass($data)) {
            header('Location: /gestion_absences22222/public/admin/classes?success=Classe ajoutée avec succès');
        } else {
            header('Location: /gestion_absences22222/public/admin/classes?error=Erreur lors de l\'ajout de la classe');
        }
        exit();
    }
}


    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => (int)$id,
                'nom_classe' => trim($_POST['nom_classe']),
                'id_salle' => isset($_POST['id_salle']) ? (int)$_POST['id_salle'] : null
            ];

            if ($this->classModel->updateClass($data)) {
                header('Location: /gestion_absences22222/public/admin/classes?success=Classe modifiée avec succès');
            } else {
                header('Location: /gestion_absences22222/public/admin/classes?error=Erreur lors de la modification de la classe');
            }
            exit();
        }
    }

    public function delete($id) {
        if ($this->classModel->deleteClass($id)) {
            header('Location: /gestion_absences22222/public/admin/classes?success=Classe supprimée avec succès');
        } else {
            header('Location: /gestion_absences22222/public/admin/classes?error=Erreur lors de la suppression de la classe');
        }
        exit();
    } 
    public function getClassModules($id_classe) {
    $modules = $this->classModel->getClassModules($id_classe);
    echo json_encode($modules);
}
    public function addModuleToClass() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nom' => trim($_POST['nom_module']),
            'id_classe' => (int)$_POST['id_classe']
        ];

        if ($this->classModel->addModule($data)) {
            header('Location: /gestion_absences22222/public/admin/classes?success=Module ajouté avec succès');
        } else {
            header('Location: /gestion_absences22222/public/admin/classes?error=Erreur lors de l\'ajout du module');
        }
        exit();
    }
}
 
    public function addModule() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => trim($_POST['nom_module']),
                'id_classe' => (int)$_POST['id_classe']
            ];

            if ($this->classModel->addModule($data)) {
                header('Location: /gestion_absences22222/public/admin/classes?success=Module ajouté avec succès');
            } else {
                header('Location: /gestion_absences22222/public/admin/classes?error=Erreur lors de l\'ajout du module');
            }
            exit();
        }
    }
public function assignProfessor() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $data = [
                'id_professeur' => (int)$_POST['id_professeur'],
                'id_module' => (int)$_POST['id_module'],
                'id_classe' => (int)$_POST['id_classe'],
                'volume_horaire' => (int)$_POST['volume_horaire']
            ];

            if ($this->classModel->assignProfessorToModule($data)) {
                header('Location: /gestion_absences22222/public/admin/classes?success=Assignation réussie');
                exit;
            }
        } catch (Exception $e) {
            // Log l'erreur
            error_log($e->getMessage());
        }

        header('Location: /gestion_absences22222/public/admin/classes?error=Erreur d\'assignation');
        exit;
    }
    
    // Afficher le formulaire
    $modules = $this->classModel->getAllModules();
    $professeurs = $this->classModel->getAllProfesseurs();
    $classes = $this->classModel->getAllClassesWithDetails();
    
    $this->view('admin/assign_professor', [
        'modules' => $modules,
        'professeurs' => $professeurs,
        'classes' => $classes
    ]);
}

    public function getClassStudents($id_classe) {
        $students = $this->classModel->getStudentsByClass($id_classe);
        echo json_encode($students);
    }
}