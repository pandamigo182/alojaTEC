<?php
/**
 * Vista: Editar Perfil de Usuario
 */

$titulo = 'Editar Perfil';

ob_start();
?>

<div class="container">
    <div class="form-container">
        <h2 class="form-title">
            <i class="fas fa-user-edit"></i>
            Editar Mi Perfil
        </h2>
        
        <form method="POST" action="/CRUD/actualizar-perfil">
            <div class="form-group">
                <label for="name">
                    <i class="fas fa-user"></i> Nombre Completo
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       required 
                       value="<?= htmlspecialchars($usuario['name']) ?>">
            </div>
            
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i> Correo Electrónico
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       required 
                       value="<?= htmlspecialchars($usuario['email']) ?>">
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="/CRUD/mi-cuenta" class="btn btn-secondary" style="flex: 0.5;">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
        
        <hr style="margin: 2rem 0; border: none; border-top: 1px solid var(--light-gray);">
        
        <div class="text-center">
            <a href="/CRUD/cambiar-password" class="btn btn-secondary btn-sm">
                <i class="fas fa-key"></i> Cambiar Contraseña
            </a>
        </div>
    </div>
</div>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
