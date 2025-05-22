<?php 
require_once __DIR__.'/../../core/Model.php';

class AdminModel extends Model
{
    public function countEtudiants(): int
    {
        try {
            $sql = "SELECT COUNT(id) as total FROM users WHERE role = 'etudiant'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return (int)$result->total;
        } catch (PDOException $e) {
            error_log("Erreur countEtudiants: " . $e->getMessage());
            return 0;
        }
    }

    public function countProfesseurs(): int
    {
        try {
            $sql = "SELECT COUNT(id) as total FROM users WHERE role = 'professeur'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return (int)$result->total;
        } catch (PDOException $e) {
            error_log("Erreur countProfesseurs: " . $e->getMessage());
            return 0;
        }
    }

    public function countAbsences(array $filters = []): int
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM absences a
                    JOIN seances s ON a.id_seance = s.id
                    WHERE DATE(s.date_seance) = :date";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':date' => $filters['date'] ?? date('Y-m-d')]);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return (int)$result->total;
        } catch (PDOException $e) {
            error_log("Erreur countAbsences: " . $e->getMessage());
            return 0;
        }
    }



    public function getTodayClasses()
    {
        $sql = "SELECT s.*, m.nom AS module, c.nom_classe 
                FROM seances s
                JOIN modules m ON s.id_module = m.id
                JOIN classes c ON s.id_classe = c.id
                WHERE DATE(s.date_seance) = CURDATE()
                ORDER BY s.date_seance";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentAbsences($limit = 5)
    {
        $sql = "SELECT a.*, e.prenom, e.nom, c.nom_classe, m.nom AS module, s.date_seance, s.heure_debut, s.heure_fin
                FROM absences a
                JOIN etudiants et ON a.id_etudiant = et.id
                JOIN users e ON et.id_user = e.id
                JOIN seances s ON a.id_seance = s.id
                JOIN modules m ON s.id_module = m.id
                JOIN classes c ON et.id_classe = c.id
                ORDER BY s.date_seance DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWeeklyAbsenceStats()
    {
        $stats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $stats[] = $this->countAbsences(['date' => $date]);
        }
        return $stats;
    }
}