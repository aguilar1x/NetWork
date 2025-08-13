<?php
/* 
======================================
CONFIGURACIÓN DE BASE DE DATOS
======================================
Este archivo se encarga de conectar con MySQL
y proporciona la conexión a otros archivos
*/

// Datos de conexión a la base de datos
$servername = "localhost";     // Servidor
$dbusername = "root";          // Usuario de MySQL
$dbpassword = "";              // Contraseña
$database = "network";    // Nombre de la base de datos

// Crear la conexión a MySQL
$conn = new mysqli($servername, $dbusername, $dbpassword, $database);

// Verificar si la conexión funcionó
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Clase Database simple para que user.php pueda usar la conexión
class Database {
    public static function connect() {
        global $conn;  // Usar la conexión global
        return $conn;
    }
}
?>