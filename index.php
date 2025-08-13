<?php
/* 
==============================
PÁGINA PRINCIPAL
==============================
Esta es la página principal que redirige 
a inicio.php que maneja todo el contenido
*/

require_once 'app/models/user.php';

// Siempre redirigir a inicio.php (maneja tanto usuarios logueados como visitantes)
header('Location: inicio.php');
exit();
?>