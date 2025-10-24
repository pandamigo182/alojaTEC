<?php
/**
 * Vista: Formulario de Registro
 */

$titulo = 'Registro';

ob_start();
?>

<div class="form-container">
    <h2 class="form-title">
        <i class="fas fa-user-plus"></i>
        Crear Cuenta Nueva
    </h2>
    
    <form method="POST" action="/CRUD/registro-post">
        <div class="form-group">
            <label for="name">
                <i class="fas fa-user"></i> Nombre Completo
            </label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   required 
                   placeholder="Tu nombre completo"
                   value="<?= htmlspecialchars($_SESSION['old_input']['name'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope"></i> Correo Electrónico
            </label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   required 
                   placeholder="tu@email.com"
                   value="<?= htmlspecialchars($_SESSION['old_input']['email'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="password">
                <i class="fas fa-lock"></i> Contraseña
            </label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   required 
                   placeholder="Mínimo 6 caracteres"
                   minlength="6">
        </div>
        
        <div class="form-group">
            <label for="password_confirm">
                <i class="fas fa-lock"></i> Confirmar Contraseña
            </label>
            <input type="password" 
                   id="password_confirm" 
                   name="password_confirm" 
                   required 
                   placeholder="Repite tu contraseña">
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-user-plus"></i> Registrarse
        </button>
    </form>
    
    <p class="text-center mt-3">
        ¿Ya tienes cuenta? 
        <a href="/CRUD/login" style="color: var(--dark-blue-500); font-weight: bold;">
            Inicia sesión aquí
        </a>
    </p>
</div>

<?php
// Limpiar datos antiguos
unset($_SESSION['old_input']);

$contenido = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
