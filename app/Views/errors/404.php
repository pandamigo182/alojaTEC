<?php
/**
 * Vista: Página de Error 404
 */

$titulo = 'Página No Encontrada';

ob_start();
?>

<div class="container">
    <div class="empty-state" style="padding: 5rem 2rem;">
        <i class="fas fa-exclamation-triangle" style="font-size: 5rem; color: var(--warning); margin-bottom: 2rem;"></i>
        <h1 style="font-size: 3rem; color: var(--dark-blue-900); margin-bottom: 1rem;">404</h1>
        <h2 style="color: var(--dark-blue-700); margin-bottom: 1rem;">Página No Encontrada</h2>
        <p style="font-size: 1.1rem; color: var(--gray); margin-bottom: 2rem;">
            Lo sentimos, la página que buscas no existe o ha sido movida.
        </p>
        <a href="/CRUD/" class="btn btn-primary">
            <i class="fas fa-home"></i> Volver al Inicio
        </a>
    </div>
</div>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
