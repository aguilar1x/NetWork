<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/user.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) session_start();

if (!User::isLoggedIn()) {
	echo json_encode(['success' => false, 'message' => 'No autorizado']);
	exit;
}

if (!isset($_SESSION['carrito'])) {
	$_SESSION['carrito'] = [];
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

try {
	if ($method === 'GET' && $action === 'count') {
		$count = count($_SESSION['carrito']);
		echo json_encode(['success' => true, 'count' => $count]);
		exit;
	}

	if ($method === 'POST' && $action === 'add') {
		$courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
		if ($courseId <= 0) {
			echo json_encode(['success' => false, 'message' => 'Curso inválido']);
			exit;
		}

		// Validar que el curso exista
		$stmt = $conn->prepare('SELECT id, nombre, precio, imagen FROM curso WHERE id = ?');
		$stmt->bind_param('i', $courseId);
		$stmt->execute();
		$result = $stmt->get_result();
		$curso = $result->fetch_assoc();
		if (!$curso) {
			echo json_encode(['success' => false, 'message' => 'Curso no encontrado']);
			exit;
		}

		// Evitar duplicados; almacenar marca simple
		$_SESSION['carrito'][$courseId] = true;
		$count = count($_SESSION['carrito']);
		echo json_encode(['success' => true, 'message' => 'Añadido al carrito', 'count' => $count]);
		exit;
	}

	if ($method === 'POST' && $action === 'remove') {
		$courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
		if ($courseId > 0 && isset($_SESSION['carrito'][$courseId])) {
			unset($_SESSION['carrito'][$courseId]);
		}
		$count = count($_SESSION['carrito']);
		echo json_encode(['success' => true, 'message' => 'Eliminado del carrito', 'count' => $count]);
		exit;
	}

	if ($method === 'POST' && $action === 'clear') {
		$_SESSION['carrito'] = [];
		echo json_encode(['success' => true, 'message' => 'Carrito vaciado', 'count' => 0]);
		exit;
	}

	echo json_encode(['success' => false, 'message' => 'Acción no soportada']);
} catch (Throwable $e) {
	echo json_encode(['success' => false, 'message' => 'Error del servidor']);
}


