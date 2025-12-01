<?php
/*
* Archivo: controllers/libros.php
* Objetivo: CRUD Completo con subida de imágenes
*/
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    // Recibir datos básicos
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $categoria_id = (int)$_POST['categoria_id'];
    $stock = (int)$_POST['stock'];
    
    // -- NUEVOS CAMPOS --
    $editorial = trim($_POST['editorial']);
    $anio = (int)$_POST['anio_publicacion'];
    
    // Validación simple
    if (empty($titulo) || empty($autor) || empty($editorial) || empty($anio)) {
        $_SESSION['mensaje'] = "Error: Faltan campos obligatorios.";
        $_SESSION['tipo_msg'] = "danger";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // -- LÓGICA DE IMAGEN --
    $ruta_imagen = null;
    // Si viene una imagen nueva en el formulario
    if (isset($_FILES['imagen_portada']) && $_FILES['imagen_portada']['error'] === UPLOAD_ERR_OK) {
        $nombre_archivo = time() . "_" . basename($_FILES['imagen_portada']['name']);
        $target_dir = "../assets/img/libros/";
        
        // Asegurar que la carpeta exista
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $target_file = $target_dir . $nombre_archivo;
        
        // Mover el archivo
        if (move_uploaded_file($_FILES['imagen_portada']['tmp_name'], $target_file)) {
            // Guardamos la ruta relativa para usarla en el HTML
            $ruta_imagen = "assets/img/libros/" . $nombre_archivo;
        }
    }

    try {
        if ($accion === 'crear') {
            $sql = "INSERT INTO libros (titulo, autor, categoria_id, stock, editorial, anio_publicacion, imagen_portada, activo) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
            $stmt = $pdo->prepare($sql);
            // Si no se subió imagen, $ruta_imagen será NULL, lo cual es válido en tu BD
            $stmt->execute([$titulo, $autor, $categoria_id, $stock, $editorial, $anio, $ruta_imagen]);
            
            $_SESSION['mensaje'] = "Libro creado con éxito.";
            $_SESSION['tipo_msg'] = "success";

        } elseif ($accion === 'editar') {
            if ($_SESSION['user_rol'] !== 'admin') die("Acceso denegado.");

            $id = (int)$_POST['id'];

            // Si hay nueva imagen, actualizamos todo. Si no, mantenemos la imagen vieja.
            if ($ruta_imagen) {
                $sql = "UPDATE libros SET titulo=?, autor=?, categoria_id=?, stock=?, editorial=?, anio_publicacion=?, imagen_portada=? WHERE id=?";
                $params = [$titulo, $autor, $categoria_id, $stock, $editorial, $anio, $ruta_imagen, $id];
            } else {
                $sql = "UPDATE libros SET titulo=?, autor=?, categoria_id=?, stock=?, editorial=?, anio_publicacion=? WHERE id=?";
                $params = [$titulo, $autor, $categoria_id, $stock, $editorial, $anio, $id];
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            $_SESSION['mensaje'] = "Libro actualizado.";
            $_SESSION['tipo_msg'] = "warning";
        }

        header("Location: ../views/libros/index.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error BD: " . $e->getMessage();
        $_SESSION['tipo_msg'] = "danger";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
?>