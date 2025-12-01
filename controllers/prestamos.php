<?php
/*
* Archivo: controllers/prestamos.php
* Objetivo: Gestionar flujo de aprobación, préstamos y devoluciones
*/
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    try {
        // --- 1. SOLICITAR (Usuario pide el libro) ---
        if ($accion === 'solicitar') {
            $libro_id = (int)$_POST['libro_id'];
            $usuario_id = $_SESSION['user_id'];
            $fecha_devolucion = $_POST['fecha_devolucion'];

            $pdo->beginTransaction();

            // Verificar stock
            $stmt = $pdo->prepare("SELECT stock FROM libros WHERE id = ? AND activo = 1");
            $stmt->execute([$libro_id]);
            $libro = $stmt->fetch();

            if (!$libro || $libro['stock'] < 1) {
                throw new Exception("Lo sentimos, este libro ya no tiene stock disponible.");
            }

            // Insertar con estado 'solicitado'
            $sql = "INSERT INTO prestamos (usuario_id, libro_id, fecha_devolucion_esperada, estado) VALUES (?, ?, ?, 'solicitado')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario_id, $libro_id, $fecha_devolucion]);

            // Restamos stock inmediatamente para RESERVAR el libro
            $stmt = $pdo->prepare("UPDATE libros SET stock = stock - 1 WHERE id = ?");
            $stmt->execute([$libro_id]);

            $pdo->commit();
            $_SESSION['mensaje'] = "Solicitud enviada. Espera la aprobación del administrador.";
            $_SESSION['tipo_msg'] = "info";

        // --- 2. APROBAR (Admin acepta la solicitud) ---
        } elseif ($accion === 'aprobar') {
            if ($_SESSION['user_rol'] !== 'admin') throw new Exception("Acceso denegado.");
            
            $prestamo_id = (int)$_POST['prestamo_id'];

            // Solo cambiamos el estado a 'pendiente' (el stock ya se restó al solicitar)
            $stmt = $pdo->prepare("UPDATE prestamos SET estado = 'pendiente', fecha_prestamo = NOW() WHERE id = ?");
            $stmt->execute([$prestamo_id]);

            $_SESSION['mensaje'] = "Préstamo APROBADO. El usuario puede retirar el libro.";
            $_SESSION['tipo_msg'] = "success";

        // --- 3. RECHAZAR (Admin niega la solicitud) ---
        } elseif ($accion === 'rechazar') {
            if ($_SESSION['user_rol'] !== 'admin') throw new Exception("Acceso denegado.");

            $prestamo_id = (int)$_POST['prestamo_id'];
            $libro_id = (int)$_POST['libro_id'];

            $pdo->beginTransaction();

            // Cambiamos estado a rechazado
            $stmt = $pdo->prepare("UPDATE prestamos SET estado = 'rechazado' WHERE id = ?");
            $stmt->execute([$prestamo_id]);

            // ¡IMPORTANTE! Devolvemos el stock porque no se prestó
            $stmt = $pdo->prepare("UPDATE libros SET stock = stock + 1 WHERE id = ?");
            $stmt->execute([$libro_id]);

            $pdo->commit();
            $_SESSION['mensaje'] = "Solicitud rechazada. El stock ha sido liberado.";
            $_SESSION['tipo_msg'] = "warning";

        // --- 4. DEVOLVER (Usuario entrega el libro) ---
        } elseif ($accion === 'devolver') {
            if ($_SESSION['user_rol'] !== 'admin') throw new Exception("Acceso denegado.");

            $prestamo_id = (int)$_POST['prestamo_id'];
            $libro_id = (int)$_POST['libro_id'];
            $observaciones = trim($_POST['observaciones'] ?? '');

            $pdo->beginTransaction();

            $stmt = $pdo->prepare("UPDATE prestamos SET estado = 'devuelto', fecha_devolucion_real = NOW(), observaciones = ? WHERE id = ?");
            $stmt->execute([$observaciones, $prestamo_id]);

            // Devolvemos el stock al inventario
            $stmt = $pdo->prepare("UPDATE libros SET stock = stock + 1 WHERE id = ?");
            $stmt->execute([$libro_id]);

            $pdo->commit();
            $_SESSION['mensaje'] = "Libro devuelto exitosamente.";
            $_SESSION['tipo_msg'] = "success";
        }

        header("Location: ../views/prestamos/index.php");
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $_SESSION['mensaje'] = "Error: " . $e->getMessage();
        $_SESSION['tipo_msg'] = "danger";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
?>