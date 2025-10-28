<?php
/**
 * Vista: Error 500 - Error Interno del Servidor
 */

$titulo = 'Error Interno';

ob_start();
?>

<div class="container">
    <div class="empty-state" style="padding: 4rem 2rem;">
        <i class="fas fa-bug" style="font-size: 4rem; color: var(--danger); margin-bottom: 1rem;"></i>
        <h1 style="font-size: 2rem; color: var(--dark-blue-900); margin-bottom: 0.5rem;">500 - Error Interno</h1>
        <p style="color: var(--gray); font-size: 1rem; margin-bottom: 1rem;">Ha ocurrido un error en el servidor. Por favor intenta más tarde.</p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary">
            <i class="fas fa-home"></i> Volver al Inicio
        </a>
    </div>
</div>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
