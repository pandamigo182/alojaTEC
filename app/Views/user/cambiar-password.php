<?php
/**
 * Vista: Cambiar Contraseña
 */

$titulo = 'Cambiar Contraseña';

ob_start();
?>

<div class="container">
    <div class="form-container">
        <h2 class="form-title">
            <i class="fas fa-key"></i>
            Cambiar Contraseña
        </h2>
        
        <form method="POST" action="/CRUD/procesar-cambio-password">
            <div class="form-group">
                <label for="password_actual">
                    <i class="fas fa-lock"></i> Contraseña Actual
                </label>
                <input type="password" 
                       id="password_actual" 
                       name="password_actual" 
                       required 
                       placeholder="Tu contraseña actual">
            </div>
            
            <div class="form-group">
                <label for="password_nueva">
                    <i class="fas fa-lock"></i> Nueva Contraseña
                </label>
                <input type="password" 
                       id="password_nueva" 
                       name="password_nueva" 
                       required 
                       minlength="6"
                       placeholder="Mínimo 6 caracteres">
            </div>
            
            <div class="form-group">
                <label for="password_confirm">
                    <i class="fas fa-lock"></i> Confirmar Nueva Contraseña
                </label>
                <input type="password" 
                       id="password_confirm" 
                       name="password_confirm" 
                       required 
                       placeholder="Repite la nueva contraseña">
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Cambiar Contraseña
                </button>
                <a href="/CRUD/mi-cuenta" class="btn btn-secondary" style="flex: 0.5;">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
