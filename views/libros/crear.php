<?php 
require_once '../../config/db.php'; 
require_once '../../includes/header.php'; 

// Obtener categorías para el select
$cats = $pdo->query("SELECT * FROM categorias WHERE activo = 1")->fetchAll();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4>Registrar Nuevo Libro</h4>
            </div>
            <div class="card-body">
                <form action="../../controllers/libros.php" method="POST">
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

                    <div class="mb-3">
                        <label>Cantidad en Stock</label>
                        <input type="number" name="stock" class="form-control" value="1" min="0" required>
                    </div>

                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar Libro</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>