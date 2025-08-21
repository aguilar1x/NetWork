<?php

require_once 'app/models/user.php';

if (!User::isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Obtener información del usuario actual
$currentUser = User::getCurrentUser();
$es_admin = ($currentUser['rol'] === 1); // Solo los admin pueden crear, editar y eliminar

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Si se quiere eliminar un curso del carrito
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['carrito'][$id]);
}

// Datos de prueba 
$cursos = [
    1 => ['titulo' => 'React desde Cero a Experto', 'precio' => 49.99, 'imagen' => './img/react.jpg'],
    2 => ['titulo' => 'Diseño UX/UI con Figma', 'precio' => 39.99, 'imagen' => './img/figma.jpg'],
    3 => ['titulo' => 'Marketing Digital Completo', 'precio' => 59.99, 'imagen' => './img/marketingdigital.jpg'],
];

// Calcular total
$total = 0;
foreach ($_SESSION['carrito'] as $id => $item) {
    $total += $cursos[$id]['precio'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Carrito - NetWork</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <script src="./js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Navegación -->
    <header></header>

    <main class="container my-5">
        <h1 class="mb-4">🛒 Tu carrito</h1>

        <?php if (empty($_SESSION['carrito'])): ?>
            <p class="text-muted">Tu carrito está vacío.</p>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($_SESSION['carrito'] as $id => $item): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo $cursos[$id]['imagen']; ?>" width="60" class="me-3 rounded">
                            <div>
                                <h5 class="mb-1"><?php echo $cursos[$id]['titulo']; ?></h5>
                                <small>$<?php echo number_format($cursos[$id]['precio'], 2); ?></small>
                            </div>
                        </div>
                        <a href="?remove=<?php echo $id; ?>" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-4 d-flex justify-content-between align-items-center">
                <h4>Total: $<?php echo number_format($total, 2); ?></h4>
                <button class="btn btn-success">Finalizar compra</button>
            </div>
        <?php endif; ?>
    </main>

        <!-- Footer -->
        <footer class="footer text-center mt-1"></footer>

        <!-- Scripts -->
        <script>
        // Pasar datos del usuario si quieres mostrar nombre, carrito, etc.
        window.currentUser = {
            nombre: '<?php echo htmlspecialchars($currentUser['nombre'] ?? ''); ?>'
        };
        </script>
        <script src="./js/components/header.js"></script>
        <script src="./js/components/footer.js"></script>
        <script src="./js/index.js"></script>

</body>

</html>
