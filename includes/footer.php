</div> <footer class="py-4 bg-light mt-auto border-top">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">
                Copyright &copy; Sistema de Biblioteca <?php echo date('Y'); ?>
            </div>
            <div>
                <a href="#" class="text-decoration-none text-muted">Política de Privacidad</a>
                &middot;
                <a href="#" class="text-decoration-none text-muted">Términos y Condiciones</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Seleccionamos todas las alertas que tengan la clase .alert
        var alerts = document.querySelectorAll('.alert');
        
        alerts.forEach(function(alert) {
            // Esperar 4 segundos (4000 ms) y luego cerrar la alerta
            setTimeout(function() {
                // Usamos la API de Bootstrap para cerrar la alerta suavemente
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 4000);
        });
    });
</script>

</body>
</html>