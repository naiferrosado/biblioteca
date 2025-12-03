<?php 
require_once '../includes/header.php'; 

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white text-center py-3">
                <div class="mb-2">
                    <i class="fas fa-user-plus fa-4x"></i>
                </div>
                <h3 class="font-weight-light my-2">Crear Cuenta</h3>
            </div>
            <div class="card-body p-4">
                
                <?php if(isset($_SESSION['error_registro'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error_registro']; unset($_SESSION['error_registro']); ?>
                    </div>
                <?php endif; ?>

                <form action="../controllers/auth.php" method="POST">
                    <input type="hidden" name="accion" value="registrar">

                    <div class="mb-3">
                        <label for="inputNombre" class="form-label">Nombre Completo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input class="form-control" id="inputNombre" type="text" name="nombre" placeholder="Juan Pérez" required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="inputEmail" class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input class="form-control" id="inputEmail" type="email" name="email" placeholder="nombre@ejemplo.com" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label for="inputPassword" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input class="form-control" id="inputPassword" type="password" name="password" placeholder="Pass" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 mb-md-0">
                                <label for="inputPasswordConfirm" class="form-label">Confirmar</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input class="form-control" id="inputPasswordConfirm" type="password" name="password_confirm" placeholder="Confirm" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-primary btn-lg" type="submit">
                            <i class="fas fa-user-plus me-2"></i> Registrarse
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <div class="small"><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>