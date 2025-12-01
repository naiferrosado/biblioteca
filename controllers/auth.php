<?php
/*
* Archivo: controllers/auth.php
* Objetivo: Validar credenciales y crear la sesión de usuario
*/

session_start();
require_once '../config/db.php';

// Verificamos que los datos vengan por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Sanitizar entradas (Limpiar espacios, evitar código malicioso básico)
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    try {
        // 2. Buscar usuario por email
        $stmt = $pdo->prepare("SELECT id, nombre_completo, password, rol, activo FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3. Verificar si existe y si la contraseña coincide
        // password_verify() compara el texto plano con el hash guardado en la BD
        if ($usuario && password_verify($password, $usuario['password'])) {
            
            // 4. Verificar si el usuario está activo (Borrado Lógico)
            if ($usuario['activo'] == 0) {
                $_SESSION['error_login'] = "Tu cuenta ha sido desactivada. Contacta al administrador.";
                header("Location: ../views/login.php");
                exit;
            }

            // 5. ¡Login Exitoso! Guardamos datos en sesión
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nombre'] = $usuario['nombre_completo'];
            $_SESSION['user_rol'] = $usuario['rol'];

            // Redireccionar al Dashboard
            header("Location: ../views/dashboard.php");
            exit;

        } else {
            // Error: Usuario no existe o contraseña incorrecta
            // Nota de seguridad: No especifiques cuál de los dos falló para no dar pistas a hackers
            $_SESSION['error_login'] = "Credenciales incorrectas.";
            header("Location: ../views/login.php");
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['error_login'] = "Error en el sistema: " . $e->getMessage();
        header("Location: ../views/login.php");
        exit;
    }
} else {
    // Si intentan entrar directo a este archivo sin enviar formulario
    header("Location: ../views/login.php");
    exit;
}
?>