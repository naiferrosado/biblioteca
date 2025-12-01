<?php 
require_once '../../config/db.php'; 
require_once '../../includes/header.php'; 

if ($_SESSION['user_rol'] !== 'admin') exit("Acceso denegado");

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM libros WHERE id = ?");
$stmt->execute([$id]);
$libro = $stmt->fetch();
if (!$libro) header("Location: index.php");

$cats = $pdo->query("SELECT * FROM categorias WHERE activo = 1")->fetchAll();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-warning mb-3">
            <div class="card-header bg-warning text-dark">
                <h4>Editar Libro: <?= htmlspecialchars($libro['titulo']) ?></h4>
            </div>
            <div class="card-body">
                <form action="../../controllers/libros.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="id" value="<?= $libro['id'] ?>">
                    
                    <div class="mb-3">
                        <label>Título</label>
                        <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($libro['titulo']) ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Autor</label>
                            <input type="text" name="autor" class="form-control" value="<?= htmlspecialchars($libro['autor']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Categoría</label>
                            <select name="categoria_id" class="form-select" required>
                                <?php foreach($cats as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $libro['categoria_id']) ? 'selected' : '' ?>>
                                        <?= $cat['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Editorial</label>
                            <input type="text" name="editorial" class="form-control" value="<?= htmlspecialchars($libro['editorial']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Año Publicación</label>
                            <input type="number" name="anio_publicacion" class="form-control" value="<?= $libro['anio_publicacion'] ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Stock</label>
                            <input type="number" name="stock" class="form-control" value="<?= $libro['stock'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Cambiar Portada (Opcional)</label>
                            <input type="file" name="imagen_portada" class="form-control" accept="image/*">
                            <?php if(!empty($libro['imagen_portada'])): ?>
                                <small class="text-muted">Actual: <a href="../../<?= $libro['imagen_portada'] ?>" target="_blank">Ver imagen</a></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">Actualizar Datos</button>
                    <a href="index.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>