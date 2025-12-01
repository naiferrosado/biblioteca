<?php
/*
* Archivo: controllers/auth.php
* Objetivo: Manejar Login y Registro de usuarios
*/

session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Detectar qué quiere hacer el usuario (Login o Registro)
    // Si no viene el campo 'accion', asumimos que es login por compatibilidad
    $accion = $_POST['accion'] ?? 'login';

    // --- LÓGICA DE REGISTRO ---
    if ($accion === 'registrar') {
        $nombre = trim($_POST['nombre']);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        // Validaciones básicas
        if (empty($nombre) || empty($email) || empty($password)) {
            $_SESSION['error_registro'] = "Todos los campos son obligatorios.";
            header("Location: ../views/registro.php");
            exit;
        }

        if ($password !== $password_confirm) {
            $_SESSION['error_registro'] = "Las contraseñas no coinciden.";
            header("Location: ../views/registro.php");
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['error_registro'] = "La contraseña debe tener al menos 6 caracteres.";
            header("Location: ../views/registro.php");
            exit;
        }

        try {
            // 1. Verificar si el correo ya existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $_SESSION['error_registro'] = "Este correo ya está registrado.";
                header("Location: ../views/registro.php");
                exit;
            }

            // 2. Hashear contraseña
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);

            // 3. Insertar usuario (ROL SIEMPRE ES 'lector')
            $sql = "INSERT INTO usuarios (nombre_completo, email, password, rol, activo) VALUES (?, ?, ?, 'lector', 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $email, $pass_hash]);

            $_SESSION['mensaje'] = "Cuenta creada con éxito. ¡Ahora puedes iniciar sesión!";
            $_SESSION['tipo_msg'] = "success"; // Verde en la alerta
            header("Location: ../views/login.php"); // Mandarlo al login para que entre
            exit;

        } catch (PDOException $e) {
            $_SESSION['error_registro'] = "Error en base de datos: " . $e->getMessage();
            header("Location: ../views/registro.php");
            exit;
        }

    // --- LÓGICA DE LOGIN (La que ya tenías) ---
    } else {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);

        try {
            $stmt = $pdo->prepare("SELECT id, nombre_completo, password, rol, activo FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['password'])) {
                if ($usuario['activo'] == 0) {
                    $_SESSION['error_login'] = "Tu cuenta está desactivada.";
                    header("Location: ../views/login.php");
                    exit;
                }

                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_nombre'] = $usuario['nombre_completo'];
                $_SESSION['user_rol'] = $usuario['rol'];

                header("Location: ../views/dashboard.php");
                exit;
            } else {
                $_SESSION['error_login'] = "Credenciales incorrectas.";
                header("Location: ../views/login.php");
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['error_login'] = "Error: " . $e->getMessage();
            header("Location: ../views/login.php");
            exit;
        }
    }
}
?>