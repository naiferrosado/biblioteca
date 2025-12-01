<?php
// Archivo: includes/header.php

// 1. Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Configuración de Ruta Maestra
// Asegúrate que este nombre coincida con tu carpeta en htdocs
$base_url = "/biblioteca-proyecto"; 

// 3. Lógica para obtener la Foto de Perfil (Miniatura del Navbar)
$foto_nav = null;
if (isset($_SESSION['user_id'])) {
    // Usamos __DIR__ para que la ruta sea relativa a ESTE archivo (header.php)
    // Esto evita errores si incluyes el header desde carpetas profundas
    require_once __DIR__ . '/../config/db.php'; 
    
    try {
        $stmt_nav = $pdo->prepare("SELECT foto_perfil FROM usuarios WHERE id = ?");
        $stmt_nav->execute([$_SESSION['user_id']]);
        $user_nav = $stmt_nav->fetch();
        $foto_nav = $user_nav['foto_perfil'] ?? null;
    } catch (Exception $e) {
        // Si falla la consulta de la foto, no rompemos la página, solo no mostramos foto
        $foto_nav = null; 
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            background-color: #f8f9fa; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-brand { font-weight: bold; }
        /* Ajuste para que el footer siempre quede abajo si se usa flex en body */
        .container { flex: 1; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="<?= $base_url ?>/index.php">
        <i class="fas fa-book-reader"></i> Biblioteca
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/views/dashboard.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/views/libros/index.php">Libros</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/views/prestamos/index.php">Préstamos</a></li>
            
            <li class="nav-item dropdown ms-lg-3">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    
                    <?php if(!empty($foto_nav) && file_exists($_SERVER['DOCUMENT_ROOT'] . $base_url . "/" . $foto_nav)): ?>
                        <img src="<?= $base_url ?>/<?= $foto_nav ?>" alt="User" class="rounded-circle border border-white" style="width: 35px; height: 35px; object-fit: cover; margin-right: 8px;">
                    <?php else: ?>
                        <i class="fas fa-user-circle fa-2x me-2"></i>
                    <?php endif; ?>

                    <span class="fw-bold"><?= htmlspecialchars($_SESSION['user_nombre']); ?></span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li>
                        <a class="dropdown-item" href="<?= $base_url ?>/views/perfil.php">
                            <i class="fas fa-user-cog me-2 text-primary"></i> Mi Perfil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?= $base_url ?>/logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </li>

        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/views/login.php"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/views/registro.php"><i class="fas fa-user-plus"></i> Registrarse</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container pb-5">