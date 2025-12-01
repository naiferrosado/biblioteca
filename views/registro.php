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

                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputNombre" type="text" name="nombre" placeholder="Juan Pérez" required />
                        <label for="inputNombre">Nombre Completo</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input class="form-control" id="inputEmail" type="email" name="email" placeholder="nombre@ejemplo.com" required />
                        <label for="inputEmail">Correo Electrónico</label>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3 mb-md-0">
                                <input class="form-control" id="inputPassword" type="password" name="password" placeholder="Pass" required />
                                <label for="inputPassword">Contraseña</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3 mb-md-0">
                                <input class="form-control" id="inputPasswordConfirm" type="password" name="password_confirm" placeholder="Confirm" required />
                                <label for="inputPasswordConfirm">Confirmar</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-primary btn-lg" type="submit">Registrarse</button>
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