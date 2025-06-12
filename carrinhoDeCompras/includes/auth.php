<?php
require_once 'db.php';

class Auth {
    public static function login($username, $password) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            return true;
        }
        return false;
    }

    public static function logout() {
        session_unset();
        session_destroy();
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin() {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
    }

    public static function register($username, $email, $password, $full_name) {
        $db = Database::getConnection();
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $db->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password, $full_name]);
            return true;
        } catch (PDOException $e) {
            // Verifica se é erro de duplicação
            if ($e->getCode() == 23505) {
                return false; // Usuário ou email já existe
            }
            throw $e;
        }
    }
}
?>