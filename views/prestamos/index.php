<?php 
require_once '../../config/db.php'; 
require_once '../../includes/header.php'; 

// Filtros de vista (Admin ve todo, Usuario solo lo suyo)
$where = "";
$params = [];
if ($_SESSION['user_rol'] !== 'admin') {
    $where = "WHERE p.usuario_id = :uid";
    $params[':uid'] = $_SESSION['user_id'];
}

$sql = "SELECT p.*, l.titulo as libro_titulo, l.imagen_portada, u.nombre_completo as usuario_nombre 
        FROM prestamos p
        INNER JOIN libros l ON p.libro_id = l.id
        INNER JOIN usuarios u ON p.usuario_id = u.id
        $where
        ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$prestamos = $stmt->fetchAll();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-list-alt"></i> Gestión de Préstamos</h2>
        <a href="crear.php" class="btn btn-primary"><i class="fas fa-plus"></i> Solicitar Nuevo</a>
    </div>

    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['tipo_msg']; ?> alert-dismissible fade show">
            <?= $_SESSION['mensaje']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo_msg']); ?>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Libro</th>
                            <th>Solicitante</th>
                            <th>Estado</th>
                            <th>Fechas</th>
                            <?php if($_SESSION['user_rol'] === 'admin'): ?>
                                <th class="text-end">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($prestamos as $p): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($p['libro_titulo']) ?></strong></td>
                            <td><?= htmlspecialchars($p['usuario_nombre']) ?></td>
                            <td>
                                <?php 
                                    switch($p['estado']) {
                                        case 'solicitado':
                                            echo '<span class="badge bg-info text-dark">Solicitado</span>';
                                            break;
                                        case 'pendiente':
                                            echo '<span class="badge bg-warning text-dark">En Préstamo</span>';
                                            break;
                                        case 'devuelto':
                                            echo '<span class="badge bg-success">Devuelto</span>';
                                            break;
                                        case 'rechazado':
                                            echo '<span class="badge bg-danger">Rechazado</span>';
                                            break;
                                    }
                                ?>
                            </td>
                            <td>
                                <small class="text-muted d-block">Solicitado: <?= date('d/m/Y', strtotime($p['fecha_prestamo'])) ?></small>
                                <?php if($p['estado'] == 'pendiente'): ?>
                                    <small class="text-danger fw-bold">Vence: <?= date('d/m/Y', strtotime($p['fecha_devolucion_esperada'])) ?></small>
                                <?php endif; ?>
                            </td>
                            
                            <?php if($_SESSION['user_rol'] === 'admin'): ?>
                            <td class="text-end">
                                
                                <?php if($p['estado'] == 'solicitado'): ?>
                                    <form action="../../controllers/prestamos.php" method="POST" class="d-inline">
                                        <input type="hidden" name="accion" value="aprobar">
                                        <input type="hidden" name="prestamo_id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success" title="Aprobar Préstamo">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    <form action="../../controllers/prestamos.php" method="POST" class="d-inline" onsubmit="return confirm('¿Rechazar solicitud? El stock volverá al inventario.');">
                                        <input type="hidden" name="accion" value="rechazar">
                                        <input type="hidden" name="prestamo_id" value="<?= $p['id'] ?>">
                                        <input type="hidden" name="libro_id" value="<?= $p['libro_id'] ?>"> <button type="submit" class="btn btn-sm btn-danger" title="Rechazar">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>

                                <?php elseif($p['estado'] == 'pendiente'): ?>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalDevolucion<?= $p['id'] ?>">
                                        <i class="fas fa-undo"></i> Recibir
                                    </button>

                                    <div class="modal fade text-start" id="modalDevolucion<?= $p['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form action="../../controllers/prestamos.php" method="POST" class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Registrar Devolución</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Confirmas que recibiste el libro <strong><?= $p['libro_titulo'] ?></strong>?</p>
                                                    <input type="hidden" name="accion" value="devolver">
                                                    <input type="hidden" name="prestamo_id" value="<?= $p['id'] ?>">
                                                    <input type="hidden" name="libro_id" value="<?= $p['libro_id'] ?>">
                                                    <div class="mb-3">
                                                        <label>Observaciones:</label>
                                                        <textarea name="observaciones" class="form-control" placeholder="Estado del libro..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Confirmar Recepción</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                
                                <?php else: ?>
                                    <span class="text-muted small">--</span>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>