<?php
/* 
=======================================
MODELO DE USUARIO
=======================================
Este archivo maneja todo lo relacionado con los usuarios:
- Login y logout
- Verificación de sesiones
- Obtener información del usuario actual
*/

require_once __DIR__ . '/../config/db.php';

class User {
    private $db;

    // Constructor: se ejecuta al crear un nuevo objeto User
    public function __construct() {
        $this->db = Database::connect();
    }

    public function login($username, $password) {
        // Buscar el usuario en la base de datos
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result && password_verify($password, $result['contrasena'])) {
            if (session_status() == PHP_SESSION_NONE) session_start();
            
            // Guardar información del usuario en la sesión
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['nombre'] = $result['nombre'];
            $_SESSION['usuario'] = $result['usuario'];
            $_SESSION['rol'] = $result['id_rol'];
            
            return true;
        }
        return false;
    }

    // Verificar si hay un usuario logueado
    public static function isLoggedIn() {
        // Iniciar sesión si no está activa
        if (session_status() == PHP_SESSION_NONE) session_start();
        return isset($_SESSION['user_id']);
    }

    // Obtener el rol del usuario actual
    public static function getRole() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        return $_SESSION['rol'] ?? null;
    }

    // Obtener toda la información del usuario actual
    public static function getCurrentUser() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        if (isset($_SESSION['user_id'])) {
            return [
                'id' => $_SESSION['user_id'],
                'nombre' => $_SESSION['nombre'],
                'usuario' => $_SESSION['usuario'],
                'rol' => $_SESSION['rol']
            ];
        }
        return null;
    }
}
?>
