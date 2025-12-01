<?php 
require_once '../../config/db.php'; 
require_once '../../includes/header.php'; 

// Seguridad
if ($_SESSION['user_rol'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

// --- LÓGICA DEL BUSCADOR Y FILTROS ---
$busqueda = $_GET['q'] ?? '';
$filtro_rol = $_GET['rol'] ?? '';

$sql = "SELECT * FROM usuarios WHERE 1=1";
$params = [];

// Si hay texto en el buscador
// --- CORRECCIÓN EN views/usuarios/index.php ---

// Si hay texto en el buscador
if (!empty($busqueda)) {
    // Usamos dos marcadores diferentes: :b_nombre y :b_email
    $sql .= " AND (nombre_completo LIKE :b_nombre OR email LIKE :b_email)";
    
    // Asignamos el MISMO valor a ambos marcadores
    $params[':b_nombre'] = "%$busqueda%";
    $params[':b_email'] = "%$busqueda%";
}

// Si hay filtro de rol
if (!empty($filtro_rol)) {
    $sql .= " AND rol = :rol";
    $params[':rol'] = $filtro_rol;
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users"></i> Gestión de Usuarios</h2>
        </div>

    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['tipo_msg']; ?> alert-dismissible fade show">
            <?= $_SESSION['mensaje']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo_msg']); ?>
    <?php endif; ?>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="q" class="form-control" placeholder="Buscar por nombre o email..." value="<?= htmlspecialchars($busqueda) ?>">
                </div>
                <div class="col-md-4">
                    <select name="rol" class="form-select">
                        <option value="">-- Todos los Roles --</option>
                        <option value="admin" <?= $filtro_rol == 'admin' ? 'selected' : '' ?>>Administradores</option>
                        <option value="lector" <?= $filtro_rol == 'lector' ? 'selected' : '' ?>>Lectores</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($usuarios as $u): ?>
                        <tr class="<?= $u['activo'] == 0 ? 'table-secondary' : '' ?>">
                            <td><?= $u['id'] ?></td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($u['nombre_completo']) ?></div>
                                <div class="small text-muted"><?= htmlspecialchars($u['email']) ?></div>
                            </td>
                            <td>
                                <?php if($u['rol'] == 'admin'): ?>
                                    <span class="badge bg-primary">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Lector</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($u['activo']): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Suspendido</span>
                                <?php endif; ?>
                            </td>
                            <td><small><?= date('d/m/Y', strtotime($u['creado_en'])) ?></small></td>
                            
                            <td class="text-end">
                                <form action="../../controllers/usuarios.php" method="POST" class="d-inline">
                                    <input type="hidden" name="accion" value="toggle_estado">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <?php if($u['activo']): ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Suspender acceso">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Reactivar acceso">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    <?php endif; ?>
                                </form>

                                <a href="editar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <?php if($u['id'] != $_SESSION['user_id']): ?>
                                    <form action="../../controllers/usuarios.php" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de ELIMINAR este usuario? Esta acción es irreversible.');">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-dark" title="Eliminar definitivamente">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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