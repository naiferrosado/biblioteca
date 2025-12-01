<?php 
require_once '../../config/db.php'; 
require_once '../../includes/header.php'; 
$cats = $pdo->query("SELECT * FROM categorias WHERE activo = 1")->fetchAll();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4><i class="fas fa-plus"></i> Registrar Nuevo Libro</h4>
            </div>
            <div class="card-body">
                <form action="../../controllers/libros.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="accion" value="crear">
                    
                    <div class="mb-3">
                        <label>Título del Libro</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Autor</label>
                            <input type="text" name="autor" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Categoría</label>
                            <select name="categoria_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <?php foreach($cats as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= $cat['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Editorial</label>
                            <input type="text" name="editorial" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Año Publicación</label>
                            <input type="number" name="anio_publicacion" class="form-control" min="1900" max="<?= date('Y') ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Stock (Cantidad Física)</label>
                            <input type="number" name="stock" class="form-control" value="1" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Portada (Imagen)</label>
                            <input type="file" name="imagen_portada" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Guardar Libro</button>
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>