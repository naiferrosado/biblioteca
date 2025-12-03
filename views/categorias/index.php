<?php
require_once '../../config/db.php';
require_once '../../includes/header.php';

// Verificar admin
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
    echo "<script>window.location.href='../../index.php';</script>";
    exit;
}

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY id DESC")->fetchAll();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Categorías</h2>
        <a href="crear.php" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva Categoría</a>
    </div>

    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
        </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categorias as $cat): ?>
                        <tr>
                            <td><?= $cat['id'] ?></td>
                            <td><?= htmlspecialchars($cat['nombre']) ?></td>
                            <td>
                                <?php if($cat['activo']): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="editar.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <?php if($cat['activo']): ?>
                                    <a href="../../controllers/categorias.php?accion=eliminar&id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Desactivar esta categoría?')"><i class="fas fa-ban"></i></a>
                                <?php else: ?>
                                    <a href="../../controllers/categorias.php?accion=activar&id=<?= $cat['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('¿Activar esta categoría?')"><i class="fas fa-check"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
