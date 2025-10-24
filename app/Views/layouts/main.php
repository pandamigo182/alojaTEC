<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'AlojaTEC' ?> - Plataforma de Alojamientos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="<?= BASE_URL ?>/" class="navbar-logo">
                <i class="fas fa-home"></i>
                <span>AlojaTEC</span>
            </a>
            
            <ul class="navbar-menu">
                <li><a href="<?= BASE_URL ?>/">Inicio</a></li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="<?= BASE_URL ?>/admin"><i class="fas fa-cog"></i> Panel Admin</a></li>
                    <?php else: ?>
                        <li><a href="<?= BASE_URL ?>/mi-cuenta"><i class="fas fa-heart"></i> Mis Favoritos</a></li>
                    <?php endif; ?>
                    <li><a href="<?= BASE_URL ?>/logout" class="btn-secondary"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>/login">Iniciar Sesión</a></li>
                    <li><a href="<?= BASE_URL ?>/registro" class="btn-primary">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    
    <!-- Mensajes de alerta -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="container">
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['errores'])): ?>
        <div class="container">
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <?php foreach ($_SESSION['errores'] as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['errores']); ?>
    <?php endif; ?>
    
    <!-- Contenido principal -->
    <main>
        <?php echo $contenido ?? ''; ?>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <p>&copy; <?= date('Y') ?> AlojaTEC - Desarrollado por Edwin Efrain Juárez Mezquita</p>
        <p>Plataforma de Gestión de Alojamientos</p>
    </footer>
    
    <!-- Scripts JavaScript -->
    <script src="<?= BASE_URL ?>/public/js/app.js"></script>
</body>
</html>
