<?php 
require_once '../../config/db.php'; 
require_once '../../includes/header.php'; 

// Seguridad
if ($_SESSION['user_rol'] !== 'admin') { header("Location: ../dashboard.php"); exit; }

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) { header("Location: index.php"); exit; }
?>

<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4>Editar Usuario: <?= htmlspecialchars($usuario['nombre_completo']) ?></h4>
            </div>
            <div class="card-body">
                <form action="../../controllers/usuarios.php" method="POST">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

                    <div class="mb-3">
                        <label>Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Rol de Usuario</label>
                        <select name="rol" class="form-select">
                            <option value="lector" <?= $usuario['rol'] == 'lector' ? 'selected' : '' ?>>Lector (Estudiante)</option>
                            <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                        </select>
                        <div class="form-text text-danger">¡Cuidado! Un administrador tiene control total.</div>
                    </div>

                    <div class="mb-3">
                        <label>Contraseña (Opcional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>