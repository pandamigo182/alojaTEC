-- =============================================
-- Script de Base de Datos: AlojaTEC
-- =============================================

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS alojatec_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE alojatec_db;

-- =============================================
-- Tabla: users
-- Almacena la información de usuarios y administradores
-- =============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: accommodations
-- Almacena los alojamientos disponibles
-- =============================================
CREATE TABLE IF NOT EXISTS accommodations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    location VARCHAR(150) NOT NULL,
    price_per_night DECIMAL(10, 2) NOT NULL,
    rating DECIMAL(2, 1) DEFAULT 0.0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: user_favorites
-- Tabla pivote para relacionar usuarios con sus alojamientos favoritos
-- =============================================
CREATE TABLE IF NOT EXISTS user_favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    accommodation_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (accommodation_id) REFERENCES accommodations(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, accommodation_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: reviews
-- Almacena los comentarios y reseñas de los alojamientos
-- =============================================
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    accommodation_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (accommodation_id) REFERENCES accommodations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Insertar Usuario Administrador por Defecto
-- Password: admin123 (hasheado con PASSWORD_DEFAULT)
-- =============================================
INSERT INTO users (name, email, password, role) VALUES 
('Administrador', 'admin@alojatec.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- =============================================
-- Insertar Alojamientos de Ejemplo (Precargados)
-- Ubicaciones de El Salvador y Centroamérica - Precios en USD
-- =============================================
INSERT INTO accommodations (name, description, image_path, location, price_per_night, rating) VALUES 
('Hotel Boutique Centro San Salvador', 'Elegante hotel boutique en el corazón de la ciudad. Habitaciones modernas con todas las comodidades, desayuno incluido y acceso a spa.', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800', 'San Salvador, El Salvador', 85.00, 4.8),

('Casa de Playa El Tunco', 'Hermosa casa frente al mar con vista espectacular. 3 habitaciones, piscina privada y acceso directo a la playa. Perfecto para familias y surfistas.', 'https://images.unsplash.com/photo-1602343168117-bb8ffe3e2e9f?w=800', 'El Tunco, La Libertad, El Salvador', 120.00, 4.9),

('Cabaña Rústica Montaña Café', 'Acogedora cabaña de madera en las montañas cafetaleras. Chimenea, vistas panorámicas y tours de café. Ideal para escapadas románticas.', 'https://images.unsplash.com/photo-1542718610-a1d656d1884c?w=800', 'Apaneca, Ahuachapán, El Salvador', 65.00, 4.7),

('Apartamento Moderno Escalón', 'Apartamento completamente equipado en zona céntrica de Escalón. Cocina integral, WiFi de alta velocidad y espacio de trabajo. Ideal para nómadas digitales.', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800', 'Colonia Escalón, San Salvador, El Salvador', 75.00, 4.6),

('Villa Colonial Santa Ana', 'Villa histórica restaurada con arquitectura colonial. Jardines amplios, fuente central y decoración de época. Experiencia única.', 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800', 'Santa Ana, El Salvador', 95.00, 4.9),

('Loft Moderno Antigua Guatemala', 'Loft estilo moderno en la ciudad colonial de Antigua. Techos altos, arte local y cerca de cafeterías y sitios históricos.', 'https://images.unsplash.com/photo-1536376072261-38c75010e6c9?w=800', 'Antigua Guatemala, Guatemala', 110.00, 4.5),

('Hacienda Tradicional Suchitoto', 'Auténtica hacienda salvadoreña con alberca, jardines coloniales y vistas al Lago Suchitlán. Experimenta la cultura local con comodidad.', 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800', 'Suchitoto, Cuscatlán, El Salvador', 145.00, 5.0),

('Estudio Económico Universitario', 'Estudio funcional y económico cerca de universidades. Perfecto para estudiantes o viajeros con presupuesto ajustado.', 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800', 'San Salvador, El Salvador', 45.00, 4.3),

('Resort Todo Incluido Pacífico', 'Suite en resort de playa con vista al Pacífico. Restaurantes, spa, piscinas y actividades acuáticas incluidas.', 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800', 'Costa del Sol, La Paz, El Salvador', 180.00, 4.8),

('Ecolodge Parque Nacional', 'Alojamiento ecológico en Parque Nacional El Imposible. Construcción sustentable, tours guiados y conexión con la naturaleza.', 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800', 'Ahuachapán, El Salvador', 70.00, 4.7),

('Penthouse Vista Panorámica', 'Penthouse de lujo con terraza panorámica. Jacuzzi, barbacoa y las mejores vistas de San Salvador y volcanes.', 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?w=800', 'San Salvador, El Salvador', 150.00, 4.6),

('Casa Rural Ruta de las Flores', 'Casa rural en la pintoresca Ruta de las Flores. Jardines coloridos, artesanías locales y gastronomía salvadoreña. Ideal para desconectar.', 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800', 'Juayúa, Sonsonate, El Salvador', 55.00, 4.8),

('Hotel Boutique León Nicaragua', 'Hotel colonial restaurado en el centro histórico de León. Arte local, terrazas con vistas y tours culturales.', 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800', 'León, Nicaragua', 80.00, 4.6),

('Beach House Costa Rica', 'Casa de playa exclusiva en la costa del Pacífico. Piscina infinita, 4 habitaciones y vistas espectaculares al océano.', 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=800', 'Tamarindo, Guanacaste, Costa Rica', 220.00, 4.9),

('Apartamento Moderno Panamá City', 'Apartamento lujoso en rascacielos con vista al skyline. Gimnasio, piscina y ubicación céntrica en el distrito financiero.', 'https://images.unsplash.com/photo-1515263487990-61b07816b324?w=800', 'Ciudad de Panamá, Panamá', 140.00, 4.7),

('Hostal Económico Managua', 'Hostal limpio y económico cerca del aeropuerto. Desayuno incluido, WiFi y ambiente acogedor para viajeros.', 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=800', 'Managua, Nicaragua', 25.00, 4.2),

('Villa Lago Atitlán', 'Villa con vista espectacular al Lago Atitlán y volcanes. Kayaks, jardines tropicales y tranquilidad absoluta.', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=800', 'San Pedro La Laguna, Guatemala', 135.00, 5.0),

('Cabaña Playa Roatán', 'Cabaña rústica-lujosa frente al mar Caribe. Snorkel, buceo y las mejores puestas de sol de Honduras.', 'https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=800', 'Roatán, Islas de la Bahía, Honduras', 165.00, 4.8),

('Lodge Volcán Arenal', 'Lodge con vista directa al Volcán Arenal. Aguas termales, tours de aventura y biodiversidad increíble.', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800', 'La Fortuna, Alajuela, Costa Rica', 195.00, 4.9),

('Apartamento Copán Ruinas', 'Apartamento cómodo cerca de las Ruinas Mayas de Copán. Perfecto para exploradores y amantes de historia.', 'https://images.unsplash.com/photo-1528127269322-539801943592?w=800', 'Copán Ruinas, Honduras', 60.00, 4.5);

-- =============================================
-- Índices para optimizar consultas
-- =============================================
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_accommodations_location ON accommodations(location);
CREATE INDEX idx_user_favorites_user ON user_favorites(user_id);
CREATE INDEX idx_user_favorites_accommodation ON user_favorites(accommodation_id);
CREATE INDEX idx_reviews_accommodation ON reviews(accommodation_id);
CREATE INDEX idx_reviews_user ON reviews(user_id);

-- =============================================
-- Fin del Script
-- =============================================
