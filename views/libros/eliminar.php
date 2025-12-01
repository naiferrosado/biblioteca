<?php
/*
* Archivo: views/libros/eliminar.php
* Objetivo: Realizar el borrado lógico (Soft Delete)
*/

session_start();
require_once '../../config/db.php';

// 1. Seguridad de Roles: Solo admin puede borrar 
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
    $_SESSION['mensaje'] = "No tienes permisos para realizar esta acción.";
    $_SESSION['tipo_msg'] = "danger";
    header("Location: index.php");
    exit;
}

// 2. Verificar que venga el ID
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // 3. Ejecutar Borrado Lógico (activo = 0)
        $stmt = $pdo->prepare("UPDATE libros SET activo = 0 WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['mensaje'] = "Libro eliminado correctamente (Borrado Lógico).";
        $_SESSION['tipo_msg'] = "success";

    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error al eliminar: " . $e->getMessage();
        $_SESSION['tipo_msg'] = "danger";
    }
}

// 4. Redireccionar siempre al listado
header("Location: index.php");
exit;
?>