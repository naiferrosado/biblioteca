<?php
/*
* Archivo: controllers/perfil.php
* Objetivo: Permitir al usuario actualizar sus propios datos y foto
*/
session_start();
require_once '../config/db.php';

// Seguridad: Solo logueados
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user_id'];
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    
    // Validar campos básicos
    if (empty($nombre) || empty($email)) {
        $_SESSION['mensaje'] = "Nombre y correo son obligatorios.";
        $_SESSION['tipo_msg'] = "danger";
        header("Location: ../views/perfil.php");
        exit;
    }

    try {
        // 1. Validar que el email no lo use otro usuario
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("El correo electrónico ya está en uso por otro usuario.");
        }

        // 2. Manejo de la FOTO DE PERFIL
        // Recuperamos la ruta actual por si no sube una nueva
        $stmt = $pdo->prepare("SELECT foto_perfil, password FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $usuario_actual = $stmt->fetch();
        $ruta_foto = $usuario_actual['foto_perfil'];

        // Si subió una imagen nueva
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $nombre_archivo = "user_" . $id . "_" . time() . ".jpg"; // Nombre único
            $target_dir = "../assets/img/usuarios/";
            
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            
            $target_file = $target_dir . $nombre_archivo;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                $ruta_foto = "assets/img/usuarios/" . $nombre_archivo;
            }
        }

        // 3. Manejo de CONTRASEÑA
        // Si el campo de contraseña NO está vacío, la actualizamos
        $password_final = $usuario_actual['password']; // Por defecto, la vieja
        if (!empty($_POST['password'])) {
            if (strlen($_POST['password']) < 6) {
                throw new Exception("La nueva contraseña debe tener al menos 6 caracteres.");
            }
            $password_final = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        // 4. ACTUALIZAR BASE DE DATOS
        $sql = "UPDATE usuarios SET nombre_completo = ?, email = ?, password = ?, foto_perfil = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $email, $password_final, $ruta_foto, $id]);

        // 5. Actualizar la sesión para que el nombre cambie en el header
        $_SESSION['user_nombre'] = $nombre;
        
        $_SESSION['mensaje'] = "Perfil actualizado correctamente.";
        $_SESSION['tipo_msg'] = "success";

    } catch (Exception $e) {
        $_SESSION['mensaje'] = "Error: " . $e->getMessage();
        $_SESSION['tipo_msg'] = "danger";
    }

    header("Location: ../views/perfil.php");
    exit;
}
?>