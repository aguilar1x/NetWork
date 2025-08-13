<?php
/* 
==============================
PÁGINA PRINCIPAL
==============================
Esta es la página de inicio que redirige 
a los usuarios según su estado de autenticación
*/

require_once 'app/models/user.php';

// Si el usuario ya está logueado, llevarlo al inicio
if (User::isLoggedIn()) {
    header('Location: inicio.php');
    exit();
} else {
    // Si no está logueado, llevarlo al login
    header('Location: login.php');
    exit();
}
?>