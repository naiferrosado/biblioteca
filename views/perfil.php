<?php 
require_once '../config/db.php'; 
require_once '../includes/header.php'; 

// Obtener datos frescos del usuario
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$u = $stmt->fetch();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        
        <div class="col-md-4 mb-3">
            <div class="card shadow text-center p-4">
                <div class="mb-3 mx-auto">
                    <?php if(!empty($u['foto_perfil']) && file_exists("../../" . $u['foto_perfil'])): ?>
                        <img src="../../<?= $u['foto_perfil'] ?>" class="rounded-circle border border-3 border-primary" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php else: ?>
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px; font-size: 3rem;">
                            <?= strtoupper(substr($u['nombre_completo'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h4><?= htmlspecialchars($u['nombre_completo']) ?></h4>
                <p class="text-muted"><?= $u['rol'] == 'admin' ? 'Administrador' : 'Usuario Lector' ?></p>
                <small class="text-muted">Miembro desde: <?= date('M Y', strtotime($u['creado_en'])) ?></small>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 text-primary"><i class="fas fa-user-cog"></i> Editar Mis Datos</h5>
                </div>
                <div class="card-body">
                    
                    <?php if(isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-<?= $_SESSION['tipo_msg']; ?> alert-dismissible fade show">
                            <?= $_SESSION['mensaje']; unset($_SESSION['mensaje'], $_SESSION['tipo_msg']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="../controllers/perfil.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($u['nombre_completo']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($u['email']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto de Perfil</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <div class="form-text">Sube una imagen JPG o PNG para personalizar tu avatar.</div>
                        </div>

                        <hr class="my-4">
                        <h6 class="text-muted mb-3">Cambio de Contraseña (Opcional)</h6>

                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para mantener la actual">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once '../includes/footer.php'; ?>