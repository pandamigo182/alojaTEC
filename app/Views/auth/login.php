<?php
/**
 * Vista: Formulario de Login
 */

$titulo = 'Iniciar Sesión';

ob_start();
?>

<div class="form-container">
    <h2 class="form-title">
        <i class="fas fa-sign-in-alt"></i>
        Iniciar Sesión
    </h2>
    
    <form method="POST" action="<?= BASE_URL ?>/login-post">
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
                   placeholder="Tu contraseña">
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
        </button>
    </form>
    
    <p class="text-center mt-3">
        ¿No tienes cuenta? 
        <a href="<?= BASE_URL ?>/registro" style="color: var(--dark-blue-500); font-weight: bold;">
            Regístrate aquí
        </a>
    </p>
    
    <div class="alert alert-info mt-3">
        <i class="fas fa-info-circle"></i>
        <div>
            <strong>Usuario de prueba:</strong><br>
            Email: admin@alojatec.com<br>
            Contraseña: admin123
        </div>
    </div>
</div>

<?php
// Limpiar datos antiguos
unset($_SESSION['old_input']);

$contenido = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
