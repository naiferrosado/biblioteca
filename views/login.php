<?php 
// Ajustamos la ruta porque estamos dentro de la carpeta /views
require_once '../includes/header.php'; 

// Si ya está logueado, lo mandamos al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white text-center py-3">
                <h3 class="font-weight-light my-2">Iniciar Sesión</h3>
            </div>
            <div class="card-body p-4">
                
                <?php if(isset($_SESSION['error_login'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <?php 
                            echo $_SESSION['error_login']; 
                            unset($_SESSION['error_login']); // Limpiar mensaje después de mostrarlo
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="../controllers/auth.php" method="POST">
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputEmail" type="email" name="email" placeholder="nombre@ejemplo.com" required />
                        <label for="inputEmail">Correo Electrónico</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputPassword" type="password" name="password" placeholder="Contraseña" required />
                        <label for="inputPassword">Contraseña</label>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-primary btn-lg" type="submit">Ingresar</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
    <div class="small">
        <a href="registro.php">¿No tienes cuenta? ¡Regístrate aquí!</a>
    </div>
    <div class="small mt-2">
        <a href="../index.php">Volver al inicio</a>
    </div>
</div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>