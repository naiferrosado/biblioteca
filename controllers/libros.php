<?php
/*
* Archivo: controllers/libros.php
* Objetivo: Procesar la creación y edición de libros
*/
session_start();
require_once '../config/db.php';

// Validar permisos generales (Usuario debe estar logueado)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    // Recibir y sanitizar datos comunes
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $categoria_id = (int)$_POST['categoria_id'];
    $stock = (int)$_POST['stock'];

    // Validación básica: No permitir campos vacíos
    if (empty($titulo) || empty($autor) || $stock < 0) {
        $_SESSION['mensaje'] = "Error: Todos los campos son obligatorios.";
        $_SESSION['tipo_msg'] = "danger";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    try {
        if ($accion === 'crear') {
            // Lógica INSERT
            $sql = "INSERT INTO libros (titulo, autor, categoria_id, stock, activo) VALUES (?, ?, ?, ?, 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$titulo, $autor, $categoria_id, $stock]);
            
            $_SESSION['mensaje'] = "Libro registrado correctamente.";
            $_SESSION['tipo_msg'] = "success";

        } elseif ($accion === 'editar') {
            // Lógica UPDATE
            // Verificar Rol: Solo Admin puede editar
            if ($_SESSION['user_rol'] !== 'admin') {
                die("Acceso denegado.");
            }

            $id = (int)$_POST['id'];
            $sql = "UPDATE libros SET titulo = ?, autor = ?, categoria_id = ?, stock = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$titulo, $autor, $categoria_id, $stock, $id]);

            $_SESSION['mensaje'] = "Libro actualizado correctamente.";
            $_SESSION['tipo_msg'] = "warning";
        }

        header("Location: ../views/libros/index.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error en BD: " . $e->getMessage();
        $_SESSION['tipo_msg'] = "danger";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
?>