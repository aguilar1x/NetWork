<?php
/* 
==============================
CERRAR SESIÓN
==============================
Este archivo cierra la sesión del usuario
y lo redirige al login
*/

// Iniciar la sesión para poder destruirla
session_start();

// Eliminar todas las variables de sesión
session_unset();

// Destruir la sesión completamente
session_destroy();

// Redirigir al usuario al login
header('Location: login.php');
exit();
?>