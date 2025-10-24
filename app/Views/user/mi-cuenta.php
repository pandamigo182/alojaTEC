<?php
/**
 * Vista: Panel de Usuario - Mis Favoritos
 */

$titulo = 'Mi Cuenta';

ob_start();
?>

<div class="dashboard">
    <!-- Encabezado del Dashboard -->
    <div class="dashboard-header">
        <h1>
            <i class="fas fa-user-circle"></i>
            Bienvenido, <?= htmlspecialchars($usuario['name']) ?>
        </h1>
        <p>Administra tus alojamientos favoritos</p>
    </div>
    
    <div class="container">
        <!-- Opciones de Usuario -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="section-title">
                <i class="fas fa-heart"></i>
                Mis Alojamientos Favoritos
            </h2>
            <div class="flex gap-2">
                <a href="/CRUD/editar-perfil" class="btn btn-secondary btn-sm">
                    <i class="fas fa-edit"></i> Editar Perfil
                </a>
                <a href="/CRUD/" class="btn btn-primary btn-sm">
                    <i class="fas fa-search"></i> Buscar Más
                </a>
            </div>
        </div>
        
        <?php if (empty($favoritos)): ?>
            <div class="empty-state">
                <i class="fas fa-heart-broken"></i>
                <h3>No tienes alojamientos favoritos</h3>
                <p>Explora nuestra selección y guarda tus favoritos</p>
                <a href="/CRUD/" class="btn btn-primary mt-3">
                    <i class="fas fa-search"></i> Ver Alojamientos
                </a>
            </div>
        <?php else: ?>
            <div class="accommodations-grid">
                <?php foreach ($favoritos as $alojamiento): ?>
                    <div class="accommodation-card">
                        <img src="<?= htmlspecialchars($alojamiento['image_path']) ?>" 
                             alt="<?= htmlspecialchars($alojamiento['name']) ?>" 
                             class="accommodation-image"
                             onerror="this.src='https://via.placeholder.com/400x300?text=Imagen+No+Disponible'">
                        
                        <div class="accommodation-content">
                            <h3 class="accommodation-title">
                                <?= htmlspecialchars($alojamiento['name']) ?>
                            </h3>
                            
                            <p class="accommodation-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($alojamiento['location']) ?>
                            </p>
                            
                            <p class="accommodation-description">
                                <?= htmlspecialchars($alojamiento['description']) ?>
                            </p>
                            
                            <div class="accommodation-footer">
                                <div class="accommodation-price">
                                    USD $<?= number_format($alojamiento['price_per_night'], 2) ?>
                                    <span>/ noche</span>
                                </div>
                                
                                <div class="accommodation-rating">
                                    <i class="fas fa-star"></i>
                                    <?= number_format($alojamiento['rating'], 1) ?>
                                </div>
                            </div>
                            
                            <p style="color: var(--gray); font-size: 0.85rem; margin-top: 0.5rem;">
                                <i class="fas fa-clock"></i>
                                Agregado: <?= date('d/m/Y', strtotime($alojamiento['fecha_agregado'])) ?>
                            </p>
                            
                            <!-- Botón para eliminar de favoritos -->
                            <form method="POST" action="/CRUD/eliminar-favorito" style="margin-top: 1rem;">
                                <input type="hidden" name="accommodation_id" value="<?= $alojamiento['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-block" 
                                        onclick="return confirm('¿Estás seguro de eliminar este alojamiento de tus favoritos?')">
                                    <i class="fas fa-trash-alt"></i> Eliminar de Favoritos
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-4">
                <p style="color: var(--gray);">
                    Total de favoritos: <strong><?= count($favoritos) ?></strong>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
