<?php
// Si la sesión no está iniciada, la iniciamos.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="/index.php"><i class="fas fa-book-reader"></i> Biblioteca</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item"><a class="nav-link" href="/views/dashboard.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="/views/libros/index.php">Libros</a></li>
            <li class="nav-item"><a class="nav-link" href="/views/prestamos/index.php">Préstamos</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <?= htmlspecialchars($_SESSION['user_nombre']); ?>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/logout.php">Cerrar Sesión</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="/views/login.php">Iniciar Sesión</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container"> ```

#### B. Pie de Página (`includes/footer.php`)
Cierra las etiquetas y carga el JavaScript de Bootstrap.

```php
</div> <footer class="text-center py-4 mt-5 border-top">
    <p class="text-muted">&copy; <?php echo date('Y'); ?> Sistema de Gestión de Biblioteca. Proyecto Final.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>