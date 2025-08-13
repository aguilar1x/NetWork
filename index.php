<?php
/* 
==============================
PÁGINA PRINCIPAL (LANDING)
==============================
Esta es la página principal que muestra la landing
page para visitantes y redirige a usuarios logueados
*/

require_once 'app/models/user.php';

// Si el usuario ya está logueado, llevarlo a su dashboard
if (User::isLoggedIn()) {
    header('Location: inicio.php');
    exit();
}

// Si no está logueado, mostrar la landing page
include 'inicio.php';
?>