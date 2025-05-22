<?php
require_once __DIR__.'/../../core/Model.php';

class ClassModel extends Model {
    public function getAllClassesWithDetails() {
        $query = "SELECT c.*, s.nom as salle_nom, 
                  (SELECT COUNT(*) FROM etudiants e WHERE e.id_classe = c.id) as nombre_etudiants
                  FROM classes c
                  LEFT JOIN salles s ON c.id_salle = s.id
                  ORDER BY c.nom_classe";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllProfesseurs() {
    $query = "SELECT p.id, u.nom, u.prenom 
              FROM professeurs p
              JOIN users u ON p.id_user = u.id
              ORDER BY u.nom, u.prenom";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);}
    public function getAllSalles() {
    $query = "SELECT * FROM salles ORDER BY nom";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function getClassStudents($id_classe) {
    $query = "SELECT e.id, u.nom, u.prenom 
              FROM etudiants e
              JOIN users u ON e.id_user = u.id
              WHERE e.id_classe = :id_classe
              ORDER BY u.nom, u.prenom";
    $stmt = $this->db->prepare($query);
    $stmt->execute(['id_classe' => $id_classe]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function addSalle($nom_salle) {
        $query = "INSERT INTO salles (nom) VALUES (:nom)";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['nom' => $nom_salle]);
        return $this->db->lastInsertId();
    }

    public function getSalleByName($nom_salle) {
        $query = "SELECT id FROM salles WHERE nom = :nom LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['nom' => $nom_salle]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

     

    public function addClass($data) {
        $query = "INSERT INTO classes (nom_classe, id_salle) VALUES (:nom_classe, :id_salle)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function updateClass($data) {
        $query = "UPDATE classes SET nom_classe = :nom_classe, id_salle = :id_salle WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function deleteClass($id) {
        $query = "DELETE FROM classes WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    public function getClassDetails($id) {
        $query = "SELECT c.*, s.nom as salle_nom FROM classes c 
                  LEFT JOIN salles s ON c.id_salle = s.id 
                  WHERE c.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStudentsByClass($id_classe) {
        $query = "SELECT e.id, u.nom, u.prenom 
                  FROM etudiants e
                  JOIN users u ON e.id_user = u.id
                  WHERE e.id_classe = :id_classe
                  ORDER BY u.nom, u.prenom";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id_classe' => $id_classe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getClassModules($id_classe) {
        $query = "SELECT m.*, 
                  (SELECT COUNT(pmc.id) FROM prof_module_classe pmc WHERE pmc.id_module = m.id AND pmc.id_classe = :id_classe) as nombre_professeurs
                  FROM modules m
                  WHERE m.id_classe = :id_classe";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id_classe' => $id_classe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClassModulesDetails($id_classe) {
        $query = "SELECT m.id as module_id, m.nom as module_nom, 
                  GROUP_CONCAT(CONCAT(u.nom, ' ', u.prenom) SEPARATOR ', ') as professeurs,
                  pmc.volume_horaire
                  FROM modules m
                  LEFT JOIN prof_module_classe pmc ON m.id = pmc.id_module AND pmc.id_classe = :id_classe
                  LEFT JOIN professeurs p ON pmc.id_professeur = p.id
                  LEFT JOIN users u ON p.id_user = u.id
                  WHERE m.id_classe = :id_classe
                  GROUP BY m.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id_classe' => $id_classe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addModule($data) {
        $query = "INSERT INTO modules (nom, id_classe) VALUES (:nom, :id_classe)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

public function assignProfessorToModule($data) {
    // Vérification des données requises
    $required = ['id_professeur', 'id_module', 'id_classe', 'volume_horaire'];
    foreach ($required as $field) {
        if (!isset($data[$field])) {
            throw new Exception("Champ manquant: $field");
        }
    }

    // Requête de vérification
    $checkQuery = "SELECT id FROM prof_module_classe 
                  WHERE id_professeur = :id_professeur 
                  AND id_module = :id_module 
                  AND id_classe = :id_classe";
    
    $checkStmt = $this->db->prepare($checkQuery);
    $checkStmt->execute([
        'id_professeur' => $data['id_professeur'],
        'id_module' => $data['id_module'],
        'id_classe' => $data['id_classe']
    ]);

    if ($checkStmt->fetch()) {
        // UPDATE
        $query = "UPDATE prof_module_classe 
                 SET volume_horaire = :volume_horaire
                 WHERE id_professeur = :id_professeur 
                 AND id_module = :id_module 
                 AND id_classe = :id_classe";
    } else {
      
        $query = "INSERT INTO prof_module_classe 
                 (id_professeur, id_module, id_classe, volume_horaire) 
                 VALUES (:id_professeur, :id_module, :id_classe, :volume_horaire)";
    }

    $stmt = $this->db->prepare($query);
    
   
    $params = [
        'id_professeur' => $data['id_professeur'],
        'id_module' => $data['id_module'],
        'id_classe' => $data['id_classe'],
        'volume_horaire' => $data['volume_horaire']
    ];

    return $stmt->execute($params);
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

    public function getAllModules() {
        $query = "SELECT m.*, c.nom_classe 
                  FROM modules m
                  JOIN classes c ON m.id_classe = c.id
                  ORDER BY c.nom_classe, m.nom";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}