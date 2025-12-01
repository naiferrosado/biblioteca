<?php 
/*
* Archivo: index.php
* Objetivo: Página de aterrizaje (Landing Page) limpia y profesional
*/

// Iniciamos sesión para saber si el usuario ya está logueado
session_start();

// Importamos configuración (aunque no imprimiremos nada de la BD aquí)
require_once 'config/db.php'; 
require_once 'includes/header.php'; 
?>

<div class="p-5 mb-4 bg-white rounded-3 shadow-sm text-center">
    <div class="mb-4">
        <i class="fas fa-book-reader text-primary" style="font-size: 4rem;"></i>
    </div>

    <h1 class="display-5 fw-bold">Bienvenido a la Biblioteca</h1>
    <p class="col-md-8 fs-4 mx-auto text-muted">
        Gestión inteligente de préstamos, inventario de libros y usuarios.
    </p>

    <div class="mt-4">
        <?php if(isset($_SESSION['user_id'])): ?>
            <p class="lead">Hola, <strong><?= htmlspecialchars($_SESSION['user_nombre']) ?></strong>.</p>
            <a href="views/dashboard.php" class="btn btn-primary btn-lg px-4 gap-3">
                <i class="fas fa-tachometer-alt"></i> Ir al Panel de Control
            </a>
        <?php else: ?>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="views/login.php" class="btn btn-primary btn-lg px-4 gap-3">
                    Ingresar al Sistema
                </a>
                <a href="views/registro.php" class="btn btn-outline-secondary btn-lg px-4">
                    Crear Cuenta
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>