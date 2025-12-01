<?php 
require_once '../config/db.php'; 
require_once '../includes/header.php'; 

// Seguridad: Si no hay sesión, fuera de aquí
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// --- LÓGICA DE ESTADÍSTICAS (DASHBOARD) ---
$stats = [];

try {
    if ($_SESSION['user_rol'] === 'admin') {
        // 1. Total de Títulos de Libros
        $stmt = $pdo->query("SELECT COUNT(*) FROM libros WHERE activo = 1");
        $stats['total_libros'] = $stmt->fetchColumn();

        // 2. Total de Copias Disponibles (Stock físico en estantería)
        $stmt = $pdo->query("SELECT SUM(stock) FROM libros WHERE activo = 1");
        $stats['libros_disponibles'] = $stmt->fetchColumn() ?: 0;

        // 3. Préstamos Activos (Libros que la gente tiene en casa)
        // Estado 'pendiente' significa "Aprobado y en préstamo"
        $stmt = $pdo->query("SELECT COUNT(*) FROM prestamos WHERE estado = 'pendiente'");
        $stats['prestamos_activos'] = $stmt->fetchColumn();

        // 4. Solicitudes Pendientes (¡Atención requerida!)
        $stmt = $pdo->query("SELECT COUNT(*) FROM prestamos WHERE estado = 'solicitado'");
        $stats['solicitudes_pendientes'] = $stmt->fetchColumn();

        // 5. Usuarios Registrados (Lectores)
        $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'lector' AND activo = 1");
        $stats['total_usuarios'] = $stmt->fetchColumn();

    } else {
        // --- ESTADÍSTICAS PARA EL USUARIO LECTOR ---
        $uid = $_SESSION['user_id'];

        // 1. Mis Préstamos Activos
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM prestamos WHERE usuario_id = ? AND estado = 'pendiente'");
        $stmt->execute([$uid]);
        $stats['mis_prestamos'] = $stmt->fetchColumn();

        // 2. Mis Solicitudes (Esperando aprobación)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM prestamos WHERE usuario_id = ? AND estado = 'solicitado'");
        $stmt->execute([$uid]);
        $stats['mis_solicitudes'] = $stmt->fetchColumn();

        // 3. Mis Devoluciones Históricas
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM prestamos WHERE usuario_id = ? AND estado = 'devuelto'");
        $stmt->execute([$uid]);
        $stats['mis_devoluciones'] = $stmt->fetchColumn();
    }

} catch (PDOException $e) {
    echo "Error calculando estadísticas: " . $e->getMessage();
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Panel de Control</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['user_nombre']); ?></strong></li>
    </ol>

    <div class="row">
        
        <?php if($_SESSION['user_rol'] === 'admin'): ?>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4 shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h3 mb-0"><?= $stats['solicitudes_pendientes'] ?></div>
                            <div>Solicitudes Nuevas</div>
                        </div>
                        <i class="fas fa-bell fa-2x opacity-50"></i>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="prestamos/index.php">Revisar ahora</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4 shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h3 mb-0"><?= $stats['prestamos_activos'] ?></div>
                            <div>Libros Prestados</div>
                        </div>
                        <i class="fas fa-book-reader fa-2x opacity-50"></i>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="prestamos/index.php">Ver detalles</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4 shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h3 mb-0"><?= $stats['total_libros'] ?> <span class="fs-6 fw-normal">(<?= $stats['libros_disponibles'] ?> disp.)</span></div>
                            <div>Catálogo de Libros</div>
                        </div>
                        <i class="fas fa-swatchbook fa-2x opacity-50"></i>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="libros/index.php">Administrar Inventario</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-dark mb-4 shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h3 mb-0"><?= $stats['total_usuarios'] ?></div>
                            <div>Lectores Registrados</div>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-dark stretched-link" href="usuarios/index.php">Gestionar Usuarios</a>
                        <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="col-xl-4 col-md-6">
                <div class="card bg-primary text-white mb-4 shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h3 mb-0"><?= $stats['mis_prestamos'] ?></div>
                            <div>Libros en mi poder</div>
                        </div>
                        <i class="fas fa-book-open fa-2x opacity-50"></i>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="prestamos/index.php">Ver mis préstamos</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card bg-info text-white mb-4 shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h3 mb-0"><?= $stats['mis_solicitudes'] ?></div>
                            <div>Solicitudes Pendientes</div>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="prestamos/index.php">Consultar estado</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card bg-secondary text-white mb-4 shadow">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h3 mb-0"><?= $stats['mis_devoluciones'] ?></div>
                            <div>Libros Leídos (Historial)</div>
                        </div>
                        <i class="fas fa-history fa-2x opacity-50"></i>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="prestamos/index.php">Ver historial</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 mt-3">
                <div class="card border-primary shadow-sm">
                    <div class="card-body text-center py-5">
                        <h3 class="text-primary"><i class="fas fa-search"></i> ¿Buscas algo nuevo para leer?</h3>
                        <p class="text-muted">Explora nuestro catálogo y solicita tu próximo libro.</p>
                        <a href="libros/index.php" class="btn btn-outline-primary btn-lg">Explorar Catálogo</a>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>