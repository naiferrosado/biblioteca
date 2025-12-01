<?php
/*
* Archivo: views/libros/index.php
* Objetivo: Listar los libros (READ) con diseño visual completo
*/

// 1. Configuración y Seguridad
require_once '../../config/db.php';
require_once '../../includes/header.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// 2. Consulta a la Base de Datos
// Usamos LEFT JOIN para traer el nombre de la categoría en lugar del ID
// Filtramos por activo = 1 (Borrado Lógico)
try {
    $sql = "SELECT l.*, c.nombre as categoria_nombre 
            FROM libros l 
            LEFT JOIN categorias c ON l.categoria_id = c.id 
            WHERE l.activo = 1 
            ORDER BY l.id DESC";
    $stmt = $pdo->query($sql);
    $libros = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al cargar libros: " . $e->getMessage());
}
?>

<div class="container mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h2><i class="fas fa-book"></i> Catálogo de Libros</h2>
        
        <?php if($_SESSION['user_rol'] === 'admin'): ?>
            <a href="crear.php" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nuevo Libro
            </a>
        <?php endif; ?>
    </div>

    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['tipo_msg']; ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['mensaje']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo_msg']); ?>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 100px;">Portada</th>
                            <th>Título / Autor</th>
                            <th>Editorial / Año</th>
                            <th>Categoría</th>
                            <th class="text-center">Stock</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($libros) > 0): ?>
                            <?php foreach($libros as $libro): ?>
                            <tr>
                                <td class="text-center">
                                    <?php if(!empty($libro['imagen_portada']) && file_exists("../../" . $libro['imagen_portada'])): ?>
                                        <img src="../../<?= $libro['imagen_portada'] ?>" 
                                             alt="Portada" 
                                             class="img-thumbnail" 
                                             style="height: 80px; width: 60px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light text-secondary d-flex align-items-center justify-content-center mx-auto" 
                                             style="height: 80px; width: 60px; border: 1px solid #ddd; border-radius: 4px;">
                                            <i class="fas fa-book fa-2x"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    <h6 class="mb-0 fw-bold text-primary"><?= htmlspecialchars($libro['titulo']) ?></h6>
                                    <small class="text-muted"><i class="fas fa-user-edit"></i> <?= htmlspecialchars($libro['autor']) ?></small>
                                </td>
                                
                                <td>
                                    <div class="small"><?= htmlspecialchars($libro['editorial']) ?></div>
                                    <span class="badge bg-light text-dark border"><?= $libro['anio_publicacion'] ?></span>
                                </td>

                                <td>
                                    <span class="badge bg-info text-dark">
                                        <?= htmlspecialchars($libro['categoria_nombre'] ?? 'Sin Categoría') ?>
                                    </span>
                                </td>
                                
                                <td class="text-center">
                                    <?php if($libro['stock'] > 5): ?>
                                        <span class="badge bg-success rounded-pill"><?= $libro['stock'] ?></span>
                                    <?php elseif($libro['stock'] > 0): ?>
                                        <span class="badge bg-warning text-dark rounded-pill"><?= $libro['stock'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Agotado</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-end">
                                    <?php if($_SESSION['user_rol'] === 'admin'): ?>
                                        <div class="btn-group" role="group">
                                            <a href="editar.php?id=<?= $libro['id'] ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="eliminar.php?id=<?= $libro['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('¿Estás seguro de eliminar este libro? Se ocultará del sistema.');" 
                                               title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Solo Lectura</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <h4 class="text-muted">No hay libros registrados aún.</h4>
                                    <?php if($_SESSION['user_rol'] === 'admin'): ?>
                                        <a href="crear.php" class="btn btn-primary mt-3">Registrar el primero</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>