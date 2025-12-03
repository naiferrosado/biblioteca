<?php
// Archivo: controllers/categorias.php
session_start();
require_once '../config/db.php';

// Verificar si es admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$accion = $_REQUEST['accion'] ?? 'listar';

switch ($accion) {
    case 'crear':
        $nombre = trim($_POST['nombre']);
        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre de la categoría es obligatorio.";
            header("Location: ../views/categorias/crear.php");
            exit;
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO categorias (nombre, activo) VALUES (?, 1)");
            $stmt->execute([$nombre]);
            $_SESSION['mensaje'] = "Categoría creada correctamente.";
            header("Location: ../views/categorias/index.php");
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al crear categoría: " . $e->getMessage();
            header("Location: ../views/categorias/crear.php");
        }
        break;

    case 'editar':
        $id = $_POST['id'];
        $nombre = trim($_POST['nombre']);
        
        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre no puede estar vacío.";
            header("Location: ../views/categorias/editar.php?id=$id");
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE categorias SET nombre = ? WHERE id = ?");
            $stmt->execute([$nombre, $id]);
            $_SESSION['mensaje'] = "Categoría actualizada.";
            header("Location: ../views/categorias/index.php");
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al actualizar: " . $e->getMessage();
            header("Location: ../views/categorias/editar.php?id=$id");
        }
        break;

    case 'eliminar':
        // En lugar de borrar, desactivamos para no romper integridad referencial con libros
        $id = $_GET['id'];
        try {
            // Opcional: Verificar si hay libros en esta categoría antes de desactivar
            // $stmt = $pdo->prepare("UPDATE categorias SET activo = 0 WHERE id = ?");
            // Por ahora haremos un delete físico si no hay restricciones, o lógico.
            // Asumiremos lógico por seguridad.
            $stmt = $pdo->prepare("UPDATE categorias SET activo = 0 WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['mensaje'] = "Categoría desactivada.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al eliminar: " . $e->getMessage();
        }
        header("Location: ../views/categorias/index.php");
        break;
        
    case 'activar':
        $id = $_GET['id'];
        try {
            $stmt = $pdo->prepare("UPDATE categorias SET activo = 1 WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['mensaje'] = "Categoría activada.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al activar: " . $e->getMessage();
        }
        header("Location: ../views/categorias/index.php");
        break;
}
