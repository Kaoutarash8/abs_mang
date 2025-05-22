<?php
require_once __DIR__.'/../../core/Controller.php';
require_once 'C:/xampp/htdocs/gestion_absences22222/app/models/Admin/AbsenceModel.php';
require_once __DIR__.'/../../models/UserModel.php';          

class AbsencesController extends Controller {
    private $absenceModel;
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->absenceModel = new AbsenceModel();
        $this->userModel = new UserModel();
    }

    public function index() {
      
        
        $filters = [
            'class_id' => $_GET['class_id'] ?? null,
            'module_id' => $_GET['module_id'] ?? null,
            'student_id' => $_GET['student_id'] ?? null,
            'date' => $_GET['date'] ?? null,
            'type' => $_GET['type'] ?? null
        ];

        $data = [
            'absences' => $this->absenceModel->getFilteredAbsences($filters),
            'classes' => $this->absenceModel->getAllClasses(),
            'students' => $this->userModel->getStudentsWithClasses(),
            'filters' => $filters
        ];

        $this->view('admin/absences', $data);
    }

    public function create() {
        $this->requireRole(['admin', 'professeur']);
        
        $data = [
            'seances' => $this->absenceModel->getSeancesForForm(),
            'students' => $this->userModel->getStudentsWithClasses()
        ];

        $this->view('admin/absences_create', $data);
    }

    public function store() {
        $this->requireRole(['admin', 'professeur']);
        $this->validateCsrfToken($_POST['csrf_token'] ?? '');

        try {
            $data = [
                'etudiant_id' => (int)$_POST['etudiant_id'],
                'seance_id' => (int)$_POST['seance_id'],
                'type_absence' => $_POST['type_absence']
            ];

            if ($this->absenceModel->addAbsence($data)) {
                $this->flash('success', 'Absence enregistrée avec succès');
            } else {
                throw new Exception("Erreur lors de l'enregistrement de l'absence");
            }
        } catch (Exception $e) {
            $this->flash('error', $e->getMessage());
        }

        $this->redirect('/admin/absences');
    }

    public function edit($id) {
        $this->requireRole(['admin', 'professeur']);
        
        $absence = $this->absenceModel->getFilteredAbsences(['id' => $id]);
        
        if (empty($absence)) {
            $this->flash('error', 'Absence non trouvée');
            $this->redirect('/admin/absences');
        }

        $data = [
            'absence' => $absence[0],
            'seances' => $this->absenceModel->getSeancesForForm(),
            'students' => $this->userModel->getStudentsWithClasses()
        ];

        $this->view('admin/absences_edit', $data);
    }

    public function update($id) {
        $this->requireRole(['admin', 'professeur']);
        $this->validateCsrfToken($_POST['csrf_token'] ?? '');

        try {
            $data = [
                'type_absence' => $_POST['type_absence']
            ];

            if ($this->absenceModel->updateAbsence($id, $data)) {
                $this->flash('success', 'Absence mise à jour avec succès');
            } else {
                throw new Exception("Erreur lors de la mise à jour de l'absence");
            }
        } catch (Exception $e) {
            $this->flash('error', $e->getMessage());
        }

        $this->redirect('/admin/absences');
    }

    public function delete($id) {
        $this->requireRole(['admin', 'professeur']);
        $this->validateCsrfToken($_POST['csrf_token'] ?? '');

        try {
            if ($this->absenceModel->deleteAbsence($id)) {
                $this->flash('success', 'Absence supprimée avec succès');
            } else {
                throw new Exception("Erreur lors de la suppression de l'absence");
            }
        } catch (Exception $e) {
            $this->flash('error', $e->getMessage());
        }

        $this->redirect('/admin/absences');
    }
}