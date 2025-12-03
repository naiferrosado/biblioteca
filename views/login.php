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
                <div class="mb-2">
                    <i class="fas fa-user-circle fa-4x"></i>
                </div>
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
                    <div class="mb-3">
                        <label for="inputEmail" class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input class="form-control" id="inputEmail" type="email" name="email" placeholder="nombre@ejemplo.com" required />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="inputPassword" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input class="form-control" id="inputPassword" type="password" name="password" placeholder="Contraseña" required />
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-primary btn-lg" type="submit">
                            <i class="fas fa-sign-in-alt me-2"></i> Ingresar
                        </button>
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