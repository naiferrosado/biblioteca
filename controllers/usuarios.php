<?php
/*
* Archivo: controllers/usuarios.php
* Objetivo: Lógica para administrar usuarios (Editar, Suspender, Eliminar)
*/
session_start();
require_once '../config/db.php';

// Seguridad: Solo Admins
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
    header("Location: ../views/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id = (int)$_POST['id'];

    try {
        // --- SUSPENDER / ACTIVAR (Toggle) ---
        if ($accion === 'toggle_estado') {
            // Invertimos el valor: Si es 1 pasa a 0, si es 0 pasa a 1
            $stmt = $pdo->prepare("UPDATE usuarios SET activo = NOT activo WHERE id = ?");
            $stmt->execute([$id]);
            
            $_SESSION['mensaje'] = "Estado del usuario actualizado correctamente.";
            $_SESSION['tipo_msg'] = "success";

        // --- EDITAR DATOS ---
        } elseif ($accion === 'editar') {
            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            $rol = $_POST['rol'];

            // Validar que el email no lo tenga OTRO usuario
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
            $stmt->execute([$email, $id]);
            if ($stmt->rowCount() > 0) {
                throw new Exception("El correo electrónico ya está en uso por otro usuario.");
            }

            // Si envió contraseña nueva, la actualizamos. Si no, la dejamos igual.
            if (!empty($_POST['password'])) {
                $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET nombre_completo=?, email=?, rol=?, password=? WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nombre, $email, $rol, $pass, $id]);
            } else {
                $sql = "UPDATE usuarios SET nombre_completo=?, email=?, rol=? WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nombre, $email, $rol, $id]);
            }

            $_SESSION['mensaje'] = "Usuario actualizado correctamente.";
            $_SESSION['tipo_msg'] = "success";

        // --- ELIMINAR DEFINITIVAMENTE ---
        } elseif ($accion === 'eliminar') {
            // Validar que no se borre a sí mismo
            if ($id == $_SESSION['user_id']) {
                throw new Exception("No puedes eliminar tu propia cuenta mientras estás logueado.");
            }

            // Intentar borrar
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);

            $_SESSION['mensaje'] = "Usuario eliminado permanentemente.";
            $_SESSION['tipo_msg'] = "warning";
        }

    } catch (PDOException $e) {
        // Error de llave foránea (Si el usuario tiene préstamos, no se puede borrar)
        if ($e->getCode() == '23000') {
            $_SESSION['mensaje'] = "No se puede eliminar este usuario porque tiene historial de préstamos. Mejor suspéndelo.";
        } else {
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
        }
        $_SESSION['tipo_msg'] = "danger";
    } catch (Exception $e) {
        $_SESSION['mensaje'] = $e->getMessage();
        $_SESSION['tipo_msg'] = "danger";
    }

    // Volver a la lista o al formulario si es editar
    if($accion === 'editar') {
        header("Location: ../views/usuarios/index.php");
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
    exit;
}
?>