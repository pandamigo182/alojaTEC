<?php
/**
 * Vista: Panel de Administrador
 */

$titulo = 'Panel de Administración';

ob_start();
?>

<div class="dashboard">
    <!-- Encabezado del Dashboard -->
    <div class="dashboard-header">
        <h1>
            <i class="fas fa-shield-alt"></i>
            Panel de Administración
        </h1>
        <p>Gestiona los alojamientos de la plataforma</p>
    </div>
    
    <div class="container">
        <!-- Estadísticas Rápidas -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, var(--dark-blue-500), var(--dark-blue-700)); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: var(--shadow-md);">
                <h3 style="margin-bottom: 0.5rem; font-size: 2rem;"><?= $estadisticas['total'] ?></h3>
                <p style="opacity: 0.9;"><i class="fas fa-building"></i> Total Alojamientos</p>
            </div>
            
            <div style="background: linear-gradient(135deg, var(--success), #1e7e34); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: var(--shadow-md);">
                <h3 style="margin-bottom: 0.5rem; font-size: 2rem;">$<?= number_format($estadisticas['precio_promedio'], 0) ?></h3>
                <p style="opacity: 0.9;"><i class="fas fa-dollar-sign"></i> Precio Promedio</p>
            </div>
            
            <div style="background: linear-gradient(135deg, var(--warning), #d39e00); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: var(--shadow-md);">
                <h3 style="margin-bottom: 0.5rem; font-size: 2rem;"><?= number_format($estadisticas['mejor_rating'], 1) ?></h3>
                <p style="opacity: 0.9;"><i class="fas fa-star"></i> Mejor Calificación</p>
            </div>
        </div>
        
        <!-- Acciones Principales -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="section-title">
                <i class="fas fa-list"></i>
                Todos los Alojamientos
            </h2>
            <a href="<?= BASE_URL ?>/admin/agregar-alojamiento" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Agregar Nuevo Alojamiento
            </a>
        </div>
        
        <?php if (empty($alojamientos)): ?>
            <div class="empty-state">
                <i class="fas fa-home"></i>
                <h3>No hay alojamientos registrados</h3>
                <p>Comienza agregando tu primer alojamiento</p>
                <a href="<?= BASE_URL ?>/admin/agregar-alojamiento" class="btn btn-primary mt-3">
                    <i class="fas fa-plus-circle"></i> Agregar Alojamiento
                </a>
            </div>
        <?php else: ?>
            <!-- Tabla de Alojamientos -->
            <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-md);">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: var(--dark-blue-500); color: white;">
                        <tr>
                            <th style="padding: 1rem; text-align: left;">Nombre</th>
                            <th style="padding: 1rem; text-align: left;">Ubicación</th>
                            <th style="padding: 1rem; text-align: center;">Precio/Noche</th>
                            <th style="padding: 1rem; text-align: center;">Calificación</th>
                            <th style="padding: 1rem; text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alojamientos as $index => $alojamiento): ?>
                            <tr style="border-bottom: 1px solid var(--light-gray); <?= $index % 2 === 0 ? 'background: #f9f9f9;' : '' ?>">
                                <td style="padding: 1rem;">
                                    <strong><?= htmlspecialchars($alojamiento['name']) ?></strong>
                                </td>
                                <td style="padding: 1rem;">
                                    <i class="fas fa-map-marker-alt" style="color: var(--dark-blue-500);"></i>
                                    <?= htmlspecialchars($alojamiento['location']) ?>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <strong style="color: var(--dark-blue-700);">
                                        USD $<?= number_format($alojamiento['price_per_night'], 2) ?>
                                    </strong>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <span style="color: var(--warning);">
                                        <i class="fas fa-star"></i>
                                        <?= number_format($alojamiento['rating'], 1) ?>
                                    </span>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                                     <a href="<?= BASE_URL ?>/admin/editar-alojamiento?id=<?= $alojamiento['id'] ?>" 
                                           class="btn btn-sm btn-secondary"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                            <form method="POST" 
                                action="<?= BASE_URL ?>/admin/eliminar-alojamiento" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este alojamiento? Esta acción no se puede deshacer.');">
                                            <input type="hidden" name="id" value="<?= $alojamiento['id'] ?>">
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger"
                                                    title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="text-center mt-4">
                <p style="color: var(--gray);">
                    Mostrando <strong><?= count($alojamientos) ?></strong> alojamientos
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
