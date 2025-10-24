/**
 * AlojaTEC - Scripts JavaScript
 * Mejoras de interactividad y validaciones del lado del cliente
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ===================================
    // Validación de Formularios
    // ===================================
    
    // Validar formulario de registro
    const registroForm = document.querySelector('form[action*="registro-post"]');
    if (registroForm) {
        registroForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
                return false;
            }
        });
    }
    
    // Validar cambio de contraseña
    const cambioPasswordForm = document.querySelector('form[action*="procesar-cambio-password"]');
    if (cambioPasswordForm) {
        cambioPasswordForm.addEventListener('submit', function(e) {
            const passwordNueva = document.getElementById('password_nueva').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            
            if (passwordNueva !== passwordConfirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
        });
    }
    
    // ===================================
    // Auto-cerrar Alertas
    // ===================================
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000); // 5 segundos
    });
    
    // ===================================
    // Confirmación de Eliminación
    // ===================================
    const deleteButtons = document.querySelectorAll('button[type="submit"]');
    deleteButtons.forEach(function(button) {
        if (button.textContent.includes('Eliminar')) {
            button.addEventListener('click', function(e) {
                if (!confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
    
    // ===================================
    // Animaciones de Cards al Scroll
    // ===================================
    const cards = document.querySelectorAll('.accommodation-card');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const cardObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(20px)';
                entry.target.style.transition = 'all 0.5s ease';
                
                setTimeout(function() {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 100);
                
                cardObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    cards.forEach(function(card) {
        cardObserver.observe(card);
    });
    
    // ===================================
    // Preview de Imagen en Formulario Admin
    // ===================================
    const imageUrlInput = document.getElementById('image_path');
    if (imageUrlInput) {
        imageUrlInput.addEventListener('blur', function() {
            const url = this.value;
            if (url) {
                // Crear preview si no existe
                let preview = document.getElementById('image-preview');
                if (!preview) {
                    preview = document.createElement('div');
                    preview.id = 'image-preview';
                    preview.style.marginTop = '1rem';
                    this.parentElement.appendChild(preview);
                }
                
                preview.innerHTML = `
                    <p style="color: var(--gray); margin-bottom: 0.5rem;">
                        <i class="fas fa-eye"></i> Vista previa:
                    </p>
                    <img src="${url}" 
                         alt="Preview" 
                         style="max-width: 100%; height: 200px; object-fit: cover; border-radius: 8px; box-shadow: var(--shadow-md);"
                         onerror="this.parentElement.innerHTML='<p style=color:var(--danger)>No se pudo cargar la imagen</p>'">
                `;
            }
        });
    }
    
    // ===================================
    // Contador de Caracteres en Textarea
    // ===================================
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(function(textarea) {
        const maxLength = 500;
        
        // Crear contador
        const counter = document.createElement('div');
        counter.style.textAlign = 'right';
        counter.style.color = 'var(--gray)';
        counter.style.fontSize = '0.85rem';
        counter.style.marginTop = '0.25rem';
        textarea.parentElement.appendChild(counter);
        
        function updateCounter() {
            const length = textarea.value.length;
            counter.textContent = `${length} caracteres`;
            
            if (length > maxLength * 0.9) {
                counter.style.color = 'var(--danger)';
            } else {
                counter.style.color = 'var(--gray)';
            }
        }
        
        updateCounter();
        textarea.addEventListener('input', updateCounter);
    });
    
    // ===================================
    // Smooth Scroll
    // ===================================
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // ===================================
    // Loading State en Botones de Formulario
    // ===================================
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function() {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
                
                // Restaurar después de 5 segundos (por si hay error)
                setTimeout(function() {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }, 5000);
            }
        });
    });
    
    console.log('✅ AlojaTEC cargado correctamente');
});

// ===================================
// Toggle de Reseñas
// ===================================
function toggleReviews(id) {
    const reviewsPanel = document.getElementById('reviews-' + id);
    if (reviewsPanel) {
        if (reviewsPanel.style.display === 'none' || reviewsPanel.style.display === '') {
            reviewsPanel.style.display = 'block';
            reviewsPanel.style.animation = 'fadeInUp 0.3s ease';
        } else {
            reviewsPanel.style.display = 'none';
        }
    }
}
