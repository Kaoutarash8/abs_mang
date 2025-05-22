<?php
require_once __DIR__ . '/../../core/Model.php';

class AbsenceModel extends Model {
    // Récupère toutes les absences avec filtres
    public function getFilteredAbsences($filters = []) {
        $sql = "SELECT 
                    a.id,
                    a.id_etudiant AS etudiant_id,
                    a.id_seance,
                    a.type_absence,
                    s.date_seance,
                    s.heure_debut,
                    s.heure_fin,
                    s.id_module AS module_id,
                    s.id_classe AS classe_id,
                    u.nom AS etudiant_nom,
                    u.prenom AS etudiant_prenom,
                    m.nom AS module_nom,
                    c.nom_classe AS classe_nom
                FROM absences a
                JOIN etudiants e ON a.id_etudiant = e.id
                JOIN users u ON e.id_user = u.id
                JOIN seances s ON a.id_seance = s.id
                JOIN modules m ON s.id_module = m.id
                JOIN classes c ON s.id_classe = c.id
                WHERE 1=1";
        
        $params = [];
        
   
        if (!empty($filters['class_id'])) {
            $sql .= " AND s.id_classe = ?";
            $params[] = $filters['class_id'];
        }
        
        if (!empty($filters['module_id'])) {
            $sql .= " AND s.id_module = ?";
            $params[] = $filters['module_id'];
        }
        
        if (!empty($filters['student_id'])) {
            $sql .= " AND a.id_etudiant = ?";
            $params[] = $filters['student_id'];
        }
        
        if (!empty($filters['date'])) {
            $sql .= " AND s.date_seance = ?";
            $params[] = $filters['date'];
        }
        
        if (!empty($filters['type'])) {
            $sql .= " AND a.type_absence = ?";
            $params[] = $filters['type'];
        }
        
        $sql .= " ORDER BY s.date_seance DESC, s.heure_debut DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function addAbsence($data) {
        $sql = "INSERT INTO absences (id_etudiant, id_seance, type_absence) 
                VALUES (?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['etudiant_id'],
            $data['seance_id'],
            $data['type_absence']
        ]);
    }

    public function updateAbsence($id, $data) {
        $sql = "UPDATE absences SET 
                type_absence = ? 
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['type_absence'],
            $id
        ]);
    }

   
    public function getAllClasses() {
        try {
            $stmt = $this->db->query("SELECT id, nom_classe FROM classes ORDER BY nom_classe");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur getAllClasses: ' . $e->getMessage());
            return [];
        }
    }

    
    public function deleteAbsence($id) {
        $sql = "DELETE FROM absences WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

   
    public function getSeancesForForm() {
        $sql = "SELECT 
                    s.id, 
                    m.nom AS module_nom, 
                    c.nom_classe,
                    s.date_seance,
                    s.jour_semaine, 
                    s.heure_debut, 
                    s.heure_fin
                FROM seances s
                JOIN modules m ON s.id_module = m.id
                JOIN classes c ON s.id_classe = c.id
                ORDER BY s.date_seance DESC, s.heure_debut";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}