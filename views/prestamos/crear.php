<?php 
/*
* Archivo: views/prestamos/crear.php
* Objetivo: Formulario para solicitar préstamo (Create)
* Validaciones: Solo muestra libros con Stock > 0
*/

require_once '../../config/db.php'; 
require_once '../../includes/header.php'; 

// Lógica: Solo listar libros que estén ACTIVOS y tengan STOCK mayor a 0
try {
    $sql = "SELECT id, titulo, autor, stock FROM libros WHERE activo = 1 AND stock > 0 ORDER BY titulo ASC";
    $libros = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Error al cargar libros: " . $e->getMessage() . "</div>";
    exit;
}
?>

<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4><i class="fas fa-book-reader"></i> Solicitar Nuevo Préstamo</h4>
            </div>
            <div class="card-body">
                
                <?php if(count($libros) > 0): ?>
                <form action="../../controllers/prestamos.php" method="POST">
                    <input type="hidden" name="accion" value="solicitar">

                    <div class="mb-3">
                        <label class="form-label">Selecciona el Libro</label>
                        <select name="libro_id" class="form-select" required>
                            <option value="">-- Elige un libro disponible --</option>
                            <?php foreach($libros as $libro): ?>
                                <option value="<?= $libro['id'] ?>">
                                    <?= htmlspecialchars($libro['titulo']) ?> (Stock: <?= $libro['stock'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha esperada de devolución</label>
                        <input type="date" name="fecha_devolucion" class="form-control" 
                               min="<?= date('Y-m-d') ?>" 
                               value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                        <div class="form-text">Por defecto el sistema asigna 7 días.</div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="index.php" class="btn btn-secondary flex-fill">
                            <i class="fas fa-times"></i> Cancelar
                        </a>

                        <button type="submit" class="btn btn-success flex-fill btn-lg">
                            <i class="fas fa-check"></i> Confirmar Préstamo
                        </button>
                    </div>

                </form>
                <?php else: ?>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-circle fa-2x mb-2"></i><br>
                        No hay libros disponibles en stock en este momento para prestar.
                    </div>
                    <div class="text-center">
                        <a href="index.php" class="btn btn-secondary">Volver al historial</a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>