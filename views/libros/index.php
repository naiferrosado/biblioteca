<?php 
require_once '../../config/db.php'; 
require_once '../../includes/header.php'; 

// Consulta para traer libros con el nombre de su categoría (JOIN)
// Solo mostramos libros activos (activo = 1)
$sql = "SELECT l.*, c.nombre as categoria_nombre 
        FROM libros l 
        LEFT JOIN categorias c ON l.categoria_id = c.id 
        WHERE l.activo = 1 
        ORDER BY l.id DESC";
$stmt = $pdo->query($sql);
$libros = $stmt->fetchAll();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-book"></i> Gestión de Libros</h2>
        <a href="crear.php" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo Libro</a>
    </div>

    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['tipo_msg']; ?> alert-dismissible fade show">
            <?= $_SESSION['mensaje']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo_msg']); ?>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Categoría</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($libros as $libro): ?>
                        <tr>
                            <td><?= $libro['id'] ?></td>
                            <td><?= htmlspecialchars($libro['titulo']) ?></td>
                            <td><?= htmlspecialchars($libro['autor']) ?></td>
                            <td><span class="badge bg-info"><?= $libro['categoria_nombre'] ?? 'Sin Categ.' ?></span></td>
                            <td><?= $libro['stock'] ?></td>
                            <td>
                                <?php if($_SESSION['user_rol'] === 'admin'): ?>
                                    <a href="editar.php?id=<?= $libro['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    
                                    <a href="eliminar.php?id=<?= $libro['id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('¿Seguro que deseas eliminar este libro?');">
                                       <i class="fas fa-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">Solo lectura</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>