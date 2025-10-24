<?php
/**
 * Vista: Formulario para Editar Alojamiento (Admin)
 */

$titulo = 'Editar Alojamiento';

ob_start();
?>

<div class="container">
    <div class="form-container" style="max-width: 700px;">
        <h2 class="form-title">
            <i class="fas fa-edit"></i>
            Editar Alojamiento
        </h2>
        
    <form method="POST" action="<?= BASE_URL ?>/admin/procesar-editar">
            <input type="hidden" name="id" value="<?= $alojamiento['id'] ?>">
            
            <div class="form-group">
                <label for="name">
                    <i class="fas fa-building"></i> Nombre del Alojamiento *
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       required 
                       placeholder="Ej: Hotel Boutique Centro"
                       value="<?= htmlspecialchars($_SESSION['old_input']['name'] ?? $alojamiento['name']) ?>">
            </div>
            
            <div class="form-group">
                <label for="location">
                    <i class="fas fa-map-marker-alt"></i> Ubicación *
                </label>
                <input type="text" 
                       id="location" 
                       name="location" 
                       required 
                       placeholder="Ej: Ciudad de México, CDMX"
                       value="<?= htmlspecialchars($_SESSION['old_input']['location'] ?? $alojamiento['location']) ?>">
            </div>
            
            <div class="form-group">
                <label for="description">
                    <i class="fas fa-align-left"></i> Descripción *
                </label>
                <textarea id="description" 
                          name="description" 
                          required 
                          placeholder="Describe las características y comodidades del alojamiento..."
                          rows="4"><?= htmlspecialchars($_SESSION['old_input']['description'] ?? $alojamiento['description']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="image_path">
                    <i class="fas fa-image"></i> URL de la Imagen *
                </label>
                <input type="url" 
                       id="image_path" 
                       name="image_path" 
                       required 
                       placeholder="https://ejemplo.com/imagen.jpg"
                       value="<?= htmlspecialchars($_SESSION['old_input']['image_path'] ?? $alojamiento['image_path']) ?>">
                <small style="color: var(--gray); display: block; margin-top: 0.5rem;">
                    Puedes usar imágenes de Unsplash: https://images.unsplash.com/
                </small>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="price_per_night">
                        <i class="fas fa-dollar-sign"></i> Precio por Noche (USD) *
                    </label>
                    <input type="number" 
                           id="price_per_night" 
                           name="price_per_night" 
                           required 
                           min="0" 
                           step="0.01"
                           placeholder="85.00"
                           value="<?= htmlspecialchars($_SESSION['old_input']['price_per_night'] ?? $alojamiento['price_per_night']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="rating">
                        <i class="fas fa-star"></i> Calificación (0-5) *
                    </label>
                    <input type="number" 
                           id="rating" 
                           name="rating" 
                           required 
                           min="0" 
                           max="5" 
                           step="0.1"
                           placeholder="4.5"
                           value="<?= htmlspecialchars($_SESSION['old_input']['rating'] ?? $alojamiento['rating']) ?>">
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Actualizar Alojamiento
                </button>
                <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary" style="flex: 0.5;">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php
// Limpiar datos antiguos
unset($_SESSION['old_input']);

$contenido = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
