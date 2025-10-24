<?php
/**
 * Vista: Página Principal (Landing Page)
 * Muestra todos los alojamientos disponibles
 */

$titulo = 'Inicio';

ob_start();
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Bienvenido a AlojaTEC</h1>
        <p>Descubre los mejores alojamientos para tu próxima aventura</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="/CRUD/registro" class="btn btn-primary">Comienza Ahora</a>
        <?php endif; ?>
    </div>
</section>

<!-- Contenedor de Alojamientos -->
<div class="container">
    <!-- Filtros de Búsqueda -->
    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--shadow-md); margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1.5rem; color: var(--dark-blue-900);">
            <i class="fas fa-search"></i> Buscar y Filtrar Alojamientos
        </h3>
        
        <form method="GET" action="/CRUD/" id="filtro-form">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="busqueda" style="font-size: 0.9rem;">
                        <i class="fas fa-search"></i> Búsqueda
                    </label>
                    <input type="text" 
                           id="busqueda" 
                           name="busqueda" 
                           placeholder="Nombre o descripción"
                           value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>">
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="ubicacion" style="font-size: 0.9rem;">
                        <i class="fas fa-map-marker-alt"></i> Ubicación
                    </label>
                    <select id="ubicacion" name="ubicacion">
                        <option value="">Todas las ubicaciones</option>
                        <?php foreach ($ubicaciones as $ub): ?>
                            <option value="<?= htmlspecialchars($ub['location']) ?>"
                                    <?= (isset($_GET['ubicacion']) && $_GET['ubicacion'] === $ub['location']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ub['location']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="precio_min" style="font-size: 0.9rem;">
                        <i class="fas fa-dollar-sign"></i> Precio Mín.
                    </label>
                    <input type="number" 
                           id="precio_min" 
                           name="precio_min" 
                           placeholder="0"
                           min="0"
                           step="0.01"
                           value="<?= htmlspecialchars($_GET['precio_min'] ?? '') ?>">
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="precio_max" style="font-size: 0.9rem;">
                        <i class="fas fa-dollar-sign"></i> Precio Máx.
                    </label>
                    <input type="number" 
                           id="precio_max" 
                           name="precio_max" 
                           placeholder="10000"
                           min="0"
                           step="0.01"
                           value="<?= htmlspecialchars($_GET['precio_max'] ?? '') ?>">
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="rating_min" style="font-size: 0.9rem;">
                        <i class="fas fa-star"></i> Calificación Mín.
                    </label>
                    <select id="rating_min" name="rating_min">
                        <option value="">Cualquiera</option>
                        <option value="3" <?= (isset($_GET['rating_min']) && $_GET['rating_min'] === '3') ? 'selected' : '' ?>>3+ estrellas</option>
                        <option value="4" <?= (isset($_GET['rating_min']) && $_GET['rating_min'] === '4') ? 'selected' : '' ?>>4+ estrellas</option>
                        <option value="4.5" <?= (isset($_GET['rating_min']) && $_GET['rating_min'] === '4.5') ? 'selected' : '' ?>>4.5+ estrellas</option>
                        <option value="5" <?= (isset($_GET['rating_min']) && $_GET['rating_min'] === '5') ? 'selected' : '' ?>>5 estrellas</option>
                    </select>
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Buscar
                </button>
                <a href="/CRUD/" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Limpiar Filtros
                </a>
            </div>
        </form>
    </div>
    
    <h2 class="section-title">
        <i class="fas fa-building"></i>
        <?php if ($totalAlojamientos > 0): ?>
            <?= $totalAlojamientos ?> Alojamiento<?= $totalAlojamientos !== 1 ? 's' : '' ?> Encontrado<?= $totalAlojamientos !== 1 ? 's' : '' ?>
        <?php else: ?>
            Alojamientos Disponibles
        <?php endif; ?>
    </h2>
    
    <?php if (empty($alojamientos)): ?>
        <div class="empty-state">
            <i class="fas fa-home"></i>
            <h3>No hay alojamientos disponibles</h3>
            <p>Vuelve pronto para ver nuevas opciones</p>
        </div>
    <?php else: ?>
        <div class="accommodations-grid">
            <?php foreach ($alojamientos as $alojamiento): ?>
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
                        
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'user'): ?>
                            <form method="POST" action="/CRUD/agregar-favorito" style="margin-top: 1rem;">
                                <input type="hidden" name="accommodation_id" value="<?= $alojamiento['id'] ?>">
                                <input type="hidden" name="redirect" value="/">
                                
                                <?php if (in_array($alojamiento['id'], $favoritosIds ?? [])): ?>
                                    <button type="button" class="btn btn-secondary btn-block" disabled>
                                        <i class="fas fa-heart"></i> Ya en Favoritos
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-heart"></i> Agregar a Favoritos
                                    </button>
                                <?php endif; ?>
                            </form>
                        <?php elseif (!isset($_SESSION['user_id'])): ?>
                            <a href="/CRUD/login" class="btn btn-primary btn-block" style="margin-top: 1rem;">
                                <i class="fas fa-sign-in-alt"></i> Inicia sesión para guardar
                            </a>
                        <?php endif; ?>
                        
                        <!-- Botón para ver/agregar reseñas -->
                        <?php
                        require_once __DIR__ . '/../../Models/Review.php';
                        $reviewModel = new Review();
                        $totalReseñas = $reviewModel->contarReseñas($alojamiento['id']);
                        $promedioRating = $reviewModel->obtenerPromedioCalificacion($alojamiento['id']);
                        ?>
                        
                        <button type="button" 
                                class="btn btn-secondary btn-block btn-sm" 
                                style="margin-top: 0.5rem;"
                                onclick="toggleReviews(<?= $alojamiento['id'] ?>)">
                            <i class="fas fa-comments"></i> 
                            <?= $totalReseñas ?> Reseña<?= $totalReseñas !== 1 ? 's' : '' ?>
                            <?php if ($promedioRating > 0): ?>
                                (⭐ <?= $promedioRating ?>)
                            <?php endif; ?>
                        </button>
                        
                        <!-- Panel de reseñas (oculto por defecto) -->
                        <div id="reviews-<?= $alojamiento['id'] ?>" style="display: none; margin-top: 1rem; padding: 1rem; background: var(--light-gray); border-radius: 8px;">
                            <?php
                            $reseñas = $reviewModel->obtenerPorAlojamiento($alojamiento['id']);
                            if (!empty($reseñas)):
                            ?>
                                <h4 style="margin-bottom: 1rem; color: var(--dark-blue-900);">Reseñas:</h4>
                                <?php foreach ($reseñas as $reseña): ?>
                                    <div style="background: white; padding: 1rem; margin-bottom: 0.5rem; border-radius: 8px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                            <strong><?= htmlspecialchars($reseña['user_name']) ?></strong>
                                            <span style="color: var(--warning);">
                                                <?= str_repeat('⭐', $reseña['rating']) ?>
                                            </span>
                                        </div>
                                        <p style="color: var(--dark-gray); margin: 0;"><?= htmlspecialchars($reseña['comment']) ?></p>
                                        <small style="color: var(--gray);"><?= date('d/m/Y', strtotime($reseña['created_at'])) ?></small>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="color: var(--gray); text-align: center;">Aún no hay reseñas</p>
                            <?php endif; ?>
                            
                            <!-- Formulario para agregar reseña -->
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'user'): ?>
                                <?php if (!$reviewModel->usuarioYaComento($_SESSION['user_id'], $alojamiento['id'])): ?>
                                    <h4 style="margin-top: 1rem; margin-bottom: 0.5rem; color: var(--dark-blue-900);">Deja tu reseña:</h4>
                                    <form method="POST" action="/CRUD/agregar-resena">
                                        <input type="hidden" name="accommodation_id" value="<?= $alojamiento['id'] ?>">
                                        
                                        <div class="form-group" style="margin-bottom: 0.5rem;">
                                            <label for="rating-<?= $alojamiento['id'] ?>" style="font-size: 0.9rem;">Calificación:</label>
                                            <select id="rating-<?= $alojamiento['id'] ?>" name="rating" required style="padding: 0.5rem;">
                                                <option value="">Selecciona...</option>
                                                <option value="5">⭐⭐⭐⭐⭐ Excelente</option>
                                                <option value="4">⭐⭐⭐⭐ Muy bueno</option>
                                                <option value="3">⭐⭐⭐ Bueno</option>
                                                <option value="2">⭐⭐ Regular</option>
                                                <option value="1">⭐ Malo</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group" style="margin-bottom: 0.5rem;">
                                            <label for="comment-<?= $alojamiento['id'] ?>" style="font-size: 0.9rem;">Comentario:</label>
                                            <textarea id="comment-<?= $alojamiento['id'] ?>" 
                                                      name="comment" 
                                                      required 
                                                      rows="3"
                                                      minlength="10"
                                                      placeholder="Comparte tu experiencia..."
                                                      style="padding: 0.5rem;"></textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                                            <i class="fas fa-paper-plane"></i> Enviar Reseña
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <p style="color: var(--success); margin-top: 1rem; text-align: center;">
                                        <i class="fas fa-check-circle"></i> Ya has dejado una reseña
                                    </p>
                                <?php endif; ?>
                            <?php elseif (!isset($_SESSION['user_id'])): ?>
                                <p style="text-align: center; margin-top: 1rem;">
                                    <a href="/CRUD/login" style="color: var(--dark-blue-500);">Inicia sesión</a> para dejar una reseña
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Paginación -->
        <?php if ($totalPaginas > 1): ?>
            <div style="margin-top: 3rem; display: flex; justify-content: center; align-items: center; gap: 0.5rem;">
                <?php
                // Construir query string para mantener filtros
                $queryParams = $_GET;
                unset($queryParams['pagina']);
                $queryString = http_build_query($queryParams);
                $queryString = $queryString ? '&' . $queryString : '';
                ?>
                
                <!-- Botón Primera Página -->
                <?php if ($pagina > 1): ?>
                    <a href="/CRUD/?pagina=1<?= $queryString ?>" 
                       class="btn btn-sm btn-secondary">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                    <a href="/CRUD/?pagina=<?= $pagina - 1 ?><?= $queryString ?>" 
                       class="btn btn-sm btn-secondary">
                        <i class="fas fa-angle-left"></i>
                    </a>
                <?php endif; ?>
                
                <!-- Números de Página -->
                <?php
                $inicio = max(1, $pagina - 2);
                $fin = min($totalPaginas, $pagina + 2);
                
                for ($i = $inicio; $i <= $fin; $i++):
                ?>
                    <a href="/CRUD/?pagina=<?= $i ?><?= $queryString ?>" 
                       class="btn btn-sm <?= $i === $pagina ? 'btn-primary' : 'btn-secondary' ?>"
                       style="min-width: 40px;">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <!-- Botón Última Página -->
                <?php if ($pagina < $totalPaginas): ?>
                    <a href="/CRUD/?pagina=<?= $pagina + 1 ?><?= $queryString ?>" 
                       class="btn btn-sm btn-secondary">
                        <i class="fas fa-angle-right"></i>
                    </a>
                    <a href="/CRUD/?pagina=<?= $totalPaginas ?><?= $queryString ?>" 
                       class="btn btn-sm btn-secondary">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                <?php endif; ?>
            </div>
            
            <p style="text-align: center; color: var(--gray); margin-top: 1rem;">
                Página <?= $pagina ?> de <?= $totalPaginas ?>
            </p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
