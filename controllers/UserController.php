<?php
include  '../config.php';
include_once '../models/User.php';

class UserController
{
    // ✅ Get all users
    public function getAll()
    {
        global $pdo;
        $sql = "SELECT * FROM users";
        try {
            $query = $pdo->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // ✅ Add new user
    public function save($user)
    {
        global $pdo;
        $sql = "INSERT INTO users (name, surname, birthdate, email, password)
                VALUES (:name, :surname, :birthdate, :email, :password)";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':name' => $user->getName(),
                ':surname' => $user->getSurname(),
                ':birthdate' => $user->getBirthdate(),
                ':email' => $user->getEmail(),
                ':password' => $user->getPassword() // You can hash before storing
            ]);
        } catch (Exception $e) {
            die("Erreur lors de l'enregistrement : " . $e->getMessage());
        }
    }

    // ✅ Delete user by ID
    public function delete($id)
    {
        global $pdo;
        $sql = "DELETE FROM users WHERE id = :id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([':id' => $id]);
        } catch (Exception $e) {
            die("Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    // ✅ Update user info
    public function update($id, $user)
    {
        global $pdo;
        $sql = "UPDATE users 
                SET name = :name, surname = :surname, birthdate = :birthdate, email = :email, password = :password
                WHERE id = :id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':id' => $id,
                ':name' => $user->getName(),
                ':surname' => $user->getSurname(),
                ':birthdate' => $user->getBirthdate(),
                ':email' => $user->getEmail(),
                ':password' => $user->getPassword()
            ]);
        } catch (Exception $e) {
            die("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }

    // ✅ Get user by ID
    public function getById($id)
    {
        global $pdo;
        $sql = "SELECT * FROM users WHERE id = :id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([':id' => $id]);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                return new User(
                    $data['id'],
                    $data['name'],
                    $data['surname'],
                    $data['birthdate'],
                    $data['email'],
                    $data['password']
                );
            }
            return null;
        } catch (Exception $e) {
            die("Erreur lors de la récupération : " . $e->getMessage());
        }
    }

      // ✅ Get user by Email
    public function getByEmail($email)
    {
        global $pdo;
        $sql = "SELECT * FROM users WHERE email = :email";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([':email' => $email]);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                return new User(
                    $data['id'],
                    $data['name'],
                    $data['surname'],
                    $data['birthdate'],
                    $data['email'],
                    $data['password']
                );
            }
            return null;
        } catch (Exception $e) {
            die("Erreur lors de la récupération : " . $e->getMessage());
        }
    }
}
?>
