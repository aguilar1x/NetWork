<!DOCTYPE html>
<html>

<head>
    <title>Registro - NetWork</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <script src="./js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        .registro-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgb(0 0 0 / 0.1);
            padding: 3rem 2rem;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }

        .registro-container h2 {
            text-align: center;
            font-weight: 700;
            color: #222;
            margin-bottom: 2rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.8rem;
        }

        .input-group i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 1.2rem;
            pointer-events: none;
        }

        input.form-control {
            padding-left: 45px;
            border-radius: 8px;
            border: 1.5px solid #ccc;
            height: 45px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input.form-control:focus {
            border-color: #5a67d8;
            box-shadow: 0 0 6px #7f9cf5;
            outline: none;
        }

        label.form-label {
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: #444;
            display: block;
        }

        button.btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 12px 0;
            font-size: 1.15rem;
            font-weight: 700;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
            color: white;
            box-shadow: 0 8px 15px rgba(102, 126, 234, 0.4);
        }

        button.btn-primary:hover {
            background: linear-gradient(135deg, #5a67d8, #5a3ea2);
            box-shadow: 0 10px 20px rgba(90, 103, 216, 0.6);
        }

        .text-muted {
            text-align: center;
            margin-top: 1.8rem;
            font-size: 0.9rem;
            color: #666;
        }

        .text-muted a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <main class="registro-container" role="main" aria-label="Formulario de registro">
        <h2>Crear tu cuenta en NetWork</h2>

        <form action="inicio.html" method="post" novalidate>
            <div class="input-group">
                <label for="nombre" class="form-label">Nombre completo</label>
                <i class="bi bi-person-fill"></i>
                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Tu nombre completo" required />
            </div>

            <div class="input-group">
                <label for="correo" class="form-label">Correo electrónico</label>
                <i class="bi bi-envelope-fill"></i>
                <input type="email" id="correo" name="correo" class="form-control" placeholder="ejemplo@correo.com" required />
            </div>

            <div class="input-group">
                <label for="contrasena" class="form-label">Contraseña</label>
                <i class="bi bi-lock-fill"></i>
                <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Mínimo 8 caracteres" minlength="8" required />
            </div>

            <div class="input-group">
                <label for="confirmar" class="form-label">Confirmar contraseña</label>
                <i class="bi bi-lock-fill"></i>
                <input type="password" id="confirmar" name="confirmar" class="form-control" placeholder="Repite tu contraseña" minlength="8" required />
            </div>

            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>

        <p class="text-muted">
            ¿Ya tienes cuenta? <a href="inicio.html">Inicia sesión aquí</a>
        </p>
    </main>
</body>

</html>