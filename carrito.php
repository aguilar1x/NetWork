<?php

require_once 'app/models/user.php';
require_once 'app/config/db.php';

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

// Catálogo de cursos
$cursos = [];
$result = $conn->query("SELECT id, nombre, precio, imagen FROM curso");
if ($result) {
	while ($row = $result->fetch_assoc()) {
		$cursos[(int)$row['id']] = [
			'titulo' => $row['nombre'],
			'precio' => (float)$row['precio'],
			'imagen' => $row['imagen']
		];
	}
}

// Calcular total
$total = 0;
foreach (array_keys($_SESSION['carrito']) as $id) {
    if (isset($cursos[$id])) {
        $total += $cursos[$id]['precio'];
    }
}

// Recomendaciones simples (cursos no en el carrito)
$recomendados = [];
$idsInCart = array_keys($_SESSION['carrito']);
$whereNotIn = '';
if (!empty($idsInCart)) {
	$placeholders = implode(',', array_map('intval', $idsInCart));
	$whereNotIn = "WHERE id NOT IN ($placeholders)";
}
$recQuery = $conn->query("SELECT id, nombre, precio, imagen FROM curso $whereNotIn");
if ($recQuery) {
	while ($row = $recQuery->fetch_assoc()) {
		$recomendados[] = $row;
	}
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
        <div class="mb-4">
            <ol class="cart-steps">
                <li class="active"><span class="step-index">1</span> Carrito</li>
                <li><span class="step-index">2</span> Datos</li>
                <li><span class="step-index">3</span> Pago</li>
                <li><span class="step-index">4</span> Confirmación</li>
            </ol>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Tu carrito</h1>
            <div class="d-flex gap-2">
                <button id="btn-clear" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash3"></i> Vaciar</button>
                <a href="aprender.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Seguir comprando</a>
            </div>
        </div>

        <?php if (empty($_SESSION['carrito'])): ?>
            <div class="text-center text-muted py-5 border rounded-3">
                <i class="bi bi-cart3 display-6 d-block mb-3"></i>
                <p class="mb-3">Tu carrito está vacío.</p>
                <a href="aprender.php" class="btn btn-primary">Explorar cursos</a>
            </div>
            <?php if (!empty($recomendados)): ?>
            <div class="mt-5" id="recomendados">
                <h4 class="mb-3">También te puede interesar</h4>
                <div class="position-relative rec-wrapper">
                    <div class="rec-scroll d-flex gap-3 overflow-auto pb-2">
                        <?php foreach ($recomendados as $rec): ?>
                            <div class="card h-100 shadow-sm rec-card" style="flex: 0 0 18rem;">
                                <img src="<?php echo htmlspecialchars($rec['imagen']); ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title mb-1"><?php echo htmlspecialchars($rec['nombre']); ?></h6>
                                    <div class="mb-3 text-muted small">Curso online</div>
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">$<?php echo number_format((float)$rec['precio'], 2); ?></span>
                                        <button class="btn btn-outline-primary btn-sm" onclick="addToCart(<?php echo (int)$rec['id']; ?>)"><i class="bi bi-cart-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="row g-4" id="cart-row">
                <div class="col-lg-8">
                    <div class="list-group" id="cart-list">
                        <?php foreach (array_keys($_SESSION['carrito']) as $id): ?>
                            <?php if (!isset($cursos[$id])) continue; ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center cart-item" data-id="<?php echo $id; ?>">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo $cursos[$id]['imagen']; ?>" width="72" height="72" class="me-3 rounded object-fit-cover">
                                    <div>
                                        <h5 class="mb-1"><?php echo $cursos[$id]['titulo']; ?></h5>
                                        <small class="text-muted">Curso online</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold">$<?php echo number_format($cursos[$id]['precio'], 2); ?></div>
                                    <button class="btn btn-sm btn-link text-danger p-0 btn-remove"><i class="bi bi-x-circle"></i> Quitar</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card cart-summary sticky-top" style="top: 90px;">
                        <div class="card-body">
                            <h5 class="card-title d-flex align-items-center gap-2 mb-3"><i class="bi bi-receipt"></i> Resumen</h5>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-ticket-perforated"></i></span>
                                <input type="text" id="coupon" class="form-control" placeholder="Código de descuento">
                                <button class="btn btn-outline-primary" id="apply-coupon">Aplicar</button>
                            </div>
                            <div class="d-flex justify-content-between"><span>Subtotal</span><span id="subtotal">$<?php echo number_format($total, 2); ?></span></div>
                            <div class="d-flex justify-content-between text-muted"><span>Descuento</span><span id="discount">-$0.00</span></div>
                            <div class="d-flex justify-content-between text-muted"><span>IVA (21%)</span><span id="tax">$<?php echo number_format($total * 0.21, 2); ?></span></div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold"><span>Total</span><span id="grand-total">$<?php echo number_format($total * 1.21, 2); ?></span></div>
                            <button class="btn btn-success w-100 mt-3"><i class="bi bi-shield-check"></i> Finalizar compra</button>
                            <div class="mt-3 small text-muted">
                                <i class="bi bi-shield-lock"></i> Pago seguro con encriptación SSL • <i class="bi bi-arrow-repeat"></i> Devolución 7 días
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (!empty($recomendados)): ?>
            <div class="mt-5" id="recomendados">
                <h4 class="mb-3">También te puede interesar</h4>
                <div class="row g-3">
                    <?php foreach ($recomendados as $rec): ?>
                        <div class="col-sm-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                <img src="<?php echo htmlspecialchars($rec['imagen']); ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title mb-1"><?php echo htmlspecialchars($rec['nombre']); ?></h6>
                                    <div class="mb-3 text-muted small">Curso online</div>
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">$<?php echo number_format((float)$rec['precio'], 2); ?></span>
                                        <button class="btn btn-outline-primary btn-sm" onclick="addToCart(<?php echo (int)$rec['id']; ?>)"><i class="bi bi-cart-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
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
        <script src="./js/carrito.js"></script>
        <script>
        (function(){
            const basePath = window.location.pathname.replace(/[^\/]*$/, '');
            // Reservado para pequeñas inicializaciones puntuales si se necesitan
        })();
        </script>

</body>

</html>
