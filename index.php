<?php 
require_once 'config/db.php'; // Probamos la conexión
require_once 'includes/header.php'; // Probamos el diseño
?>

<div class="p-5 mb-4 bg-white rounded-3 shadow-sm text-center">
    <h1 class="display-5 fw-bold">Bienvenido a la Biblioteca</h1>
    <p class="col-md-8 fs-4 mx-auto">Gestión inteligente de préstamos y libros.</p>
    
    <?php if(isset($pdo)): ?>
        <div class="alert alert-success mt-3">
            <i class="fas fa-check-circle"></i> Conexión a Base de Datos: <strong>EXITOSA</strong>
        </div>
    <?php endif; ?>

    <a href="views/login.php" class="btn btn-primary btn-lg mt-3">Ingresar al Sistema</a>
</div>

<?php require_once 'includes/footer.php'; ?>