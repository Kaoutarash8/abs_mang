<?php
class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Méthodes d'authentification
    public function findUserByEmail($email) {
        $this->db->query('SELECT id, email, mot_de_passe, nom, prenom, role FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function login($email, $password) {
        try {
            $user = $this->findUserByEmail($email);

            if (!$user) {
                return false;
            }

            if (password_verify($password, $user->mot_de_passe)) {
                return (object)[
                    'id' => $user->id,
                    'email' => $user->email,
                    'nom' => $user->nom,
                    'prenom' => $user->prenom,
                    'role' => $user->role
                ];
            }
            
            return false;
        } catch (PDOException $e) {
            error_log('Erreur login: ' . $e->getMessage());
            return false;
        }
    }
     public function secureLogin($email, $password) {
        try {
            $user = $this->findUserByEmail($email);
            $hash = $user ? $user->mot_de_passe : '$2y$10$fakehashforsecurity';
            $isValid = password_verify($password, $hash);
            
            if ($user && $isValid) {
                return (object)[
                    'id' => $user->id,
                    'email' => $user->email,
                    'nom' => $user->nom,
                    'prenom' => $user->prenom,
                    'role' => $user->role
                ];
            }
            
            return false;
        } catch (PDOException $e) {
            error_log('Erreur secureLogin: ' . $e->getMessage());
            return false;
        }
    }

    // Gestion des utilisateurs
    public function registerWithClass($userData, $classId = null) {
        try {
            $this->db->beginTransaction();
if ($this->emailExists($userData['email'])) {
            throw new Exception("L'email généré existe déjà. Veuillez en générer un nouveau.");
        }
            // 1. Création de l'utilisateur
            $this->db->query('INSERT INTO users (nom, prenom, email, mot_de_passe, role) 
                            VALUES (:nom, :prenom, :email, :mot_de_passe, :role)');
            
            $this->db->bind(':nom', $userData['nom']);
            $this->db->bind(':prenom', $userData['prenom']);
            $this->db->bind(':email', $userData['email']);
            $this->db->bind(':mot_de_passe', $userData['mot_de_passe']);
            $this->db->bind(':role', $userData['role']);
            
            $this->db->execute();
            $userId = $this->db->lastInsertId();
           
            // 2. Création du profil spécifique
            if ($userData['role'] === 'etudiant') {
                $this->db->query('INSERT INTO etudiants (id_user, id_classe) VALUES (:user_id, :class_id)');
                $this->db->bind(':user_id', $userId);
                $this->db->bind(':class_id', $classId);
                $this->db->execute();
            } elseif ($userData['role'] === 'professeur') {
                $this->db->query('INSERT INTO professeurs (id_user) VALUES (:user_id)');
                $this->db->bind(':user_id', $userId);
                $this->db->execute();
            }

            $this->db->commit();
            return $userId;
        } 
        
         catch (PDOException $e) {
            $this->db->rollBack();
            error_log('Erreur registerWithClass: ' . $e->getMessage());
            return false;
        }
    }
public function getStudentsWithClasses($filters = []) {
    $sql = "SELECT u.id, u.nom, u.prenom, u.email, u.role, 
                   e.id as etudiant_id, 
                   c.id as classe_id, 
                   c.nom_classe
            FROM users u
            INNER JOIN etudiants e ON u.id = e.id_user
            LEFT JOIN classes c ON e.id_classe = c.id
            WHERE u.role = 'etudiant'";
    
   
    $conditions = [];
    $params = [];
    
    if (!empty($filters['search'])) {
        $search = "%{$filters['search']}%";
        $conditions[] = "(u.nom LIKE :search OR u.prenom LIKE :search OR c.nom_classe LIKE :search)";
        $params[':search'] = $search;
    }
    
    if (!empty($filters['classe_id'])) {
        $conditions[] = "c.id = :classe_id";
        $params[':classe_id'] = $filters['classe_id'];
    }
    
    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }
    
    $sql .= " ORDER BY u.nom, u.prenom";
    
    $this->db->query($sql);
    
    
    foreach ($params as $key => $value) {
        $this->db->bind($key, $value);
    }
    
    $students = $this->db->resultSet();
    
    
    foreach ($students as $student) {
        if (empty($student->nom_classe)) {
            $student->nom_classe = 'Non assigné';
        }
    }
    
    return $students;
}
public function getAllProfessors() {
    try {
        $sql = "SELECT u.id, u.nom, u.prenom, u.email, u.role, 
                       p.id as professeur_id
                FROM users u
                LEFT JOIN professeurs p ON u.id = p.id_user
                WHERE u.role = 'professeur'
                ORDER BY u.nom";
        
        $this->db->query($sql);
        $result = $this->db->resultSet();
        
        // Debug
        error_log("Professeurs trouvés: " . count($result));
        
        return $result;
    } catch (PDOException $e) {
        error_log('Erreur getAllProfessors: ' . $e->getMessage());
        return [];
    }
}

 public function createUserWithRole($userData, $role, $classId = null) {
    try {
        $this->db->beginTransaction();

        // 1. Création dans la table users
        $this->db->query('INSERT INTO users (nom, prenom, email, mot_de_passe, role) 
                         VALUES (:nom, :prenom, :email, :mot_de_passe, :role)');
        
        $this->db->bind(':nom', $userData['nom']);
        $this->db->bind(':prenom', $userData['prenom']);
        $this->db->bind(':email', $userData['email']);
        $this->db->bind(':mot_de_passe', $userData['mot_de_passe']);
        $this->db->bind(':role', $role);
        
        $this->db->execute();
        $userId = $this->db->lastInsertId();

        // 2. Création dans la table spécifique
        if ($role === 'etudiant') {
            $this->db->query('INSERT INTO etudiants (id_user, id_classe) 
                             VALUES (:user_id, :class_id)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':class_id', $classId);
        } 
        elseif ($role === 'professeur') {
            $this->db->query('INSERT INTO professeurs (id_user) 
                             VALUES (:user_id)');
            $this->db->bind(':user_id', $userId);
        }

        $this->db->execute();
        $this->db->commit();
        return $userId;
    } catch (PDOException $e) {
        $this->db->rollBack();
        error_log('Erreur createUserWithRole: ' . $e->getMessage());
        return false;
    }
}
   public function getUsersByRole($role) {
    try {
        if ($role === 'etudiant') {
            $sql = "SELECT u.id, u.nom, u.prenom, u.email, u.role, 
                           e.id as etudiant_id, c.id as classe_id, c.nom_classe
                    FROM users u
                    INNER JOIN etudiants e ON u.id = e.id_user
                    LEFT JOIN classes c ON e.id_classe = c.id
                    WHERE u.role = 'etudiant'
                    ORDER BY u.nom";
        } elseif ($role === 'professeur') {
            $sql = "SELECT u.id, u.nom, u.prenom, u.email, u.role, 
                           p.id as professeur_id
                    FROM users u
                    LEFT JOIN professeurs p ON u.id = p.id_user
                    WHERE u.role = 'professeur'
                    ORDER BY u.nom";
        } else {
            $sql = "SELECT u.id, u.nom, u.prenom, u.email, u.role 
                    FROM users u 
                    WHERE u.role = :role 
                    ORDER BY u.nom";
        }
        
        $this->db->query($sql);
        
        if ($role !== 'etudiant' && $role !== 'professeur') {
            $this->db->bind(':role', $role);
        }
        
        $result = $this->db->resultSet();
        
        // Debug temporaire
        error_log("Résultats pour $role: " . print_r($result, true));
        
        return $result;
    } catch (PDOException $e) {
        error_log("Erreur getUsersByRole($role): " . $e->getMessage());
        return [];
    }
}

    public function getUserById($id) {
        $this->db->query('SELECT id, nom, prenom, email, role FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }



public function deleteUser($userId, $userType) {
    try {
        $this->db->beginTransaction();

     
        $this->db->query("SELECT id, role FROM users WHERE id = :id");
        $this->db->bind(':id', $userId);
        $user = $this->db->single();

        if (!$user) {
            throw new Exception("Utilisateur introuvable");
        }

        if ($user->role !== $userType) {
            throw new Exception("Le type d'utilisateur ne correspond pas à son rôle");
        }

        $this->db->query("SET FOREIGN_KEY_CHECKS=0");

        if ($userType === 'etudiant') {
            // Supprimer l'étudiant (ce qui supprimera automatiquement le user via ON DELETE CASCADE)
            $this->db->query("DELETE FROM etudiants WHERE id_user = :user_id");
            $this->db->bind(':user_id', $userId);
            $this->db->execute();
        } elseif ($userType === 'professeur') {
            $this->db->query("DELETE FROM professeurs WHERE id_user = :user_id");
            $this->db->bind(':user_id', $userId);
            $this->db->execute();
        }

        $this->db->query("SET FOREIGN_KEY_CHECKS=1");

        $this->db->commit();
        return true;

    } catch (PDOException $e) {
        $this->db->rollBack();
        error_log("Erreur PDO lors de la suppression : " . $e->getMessage());
        throw new Exception("Erreur lors de la suppression de l'utilisateur: " . $e->getMessage());
    } catch (Exception $e) {
        $this->db->rollBack();
        throw $e;
    }
}

    public function generateRandomPassword($length = 12) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        
        return $password;
    }

    // Gestion des classes
    public function getAllClasses() {
        $this->db->query("SELECT id, nom_classe FROM classes ORDER BY nom_classe");
        return $this->db->resultSet();
    }

    // Utilitaires
    public function emailExists($email, $excludeId = null) {
        $sql = 'SELECT id FROM users WHERE email = :email';
        if ($excludeId) {
            $sql .= ' AND id != :exclude_id';
        }
        
        $this->db->query($sql);
        $this->db->bind(':email', $email);
        if ($excludeId) {
            $this->db->bind(':exclude_id', $excludeId);
        }
        
        return $this->db->single() !== false;
    }
}