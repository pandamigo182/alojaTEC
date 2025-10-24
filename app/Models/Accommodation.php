<?php
/**
 * Modelo Accommodation
 * 
 * Maneja toda la lógica relacionada con los alojamientos,
 * incluyendo listado, creación, búsqueda y gestión.
 */

require_once __DIR__ . '/../Database.php';

class Accommodation {
    
    /**
     * Instancia de la base de datos
     * @var Database
     */
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtiene alojamientos con filtros y paginación
     * 
     * @param array $filtros Array con los filtros (busqueda, ubicacion, precio_min, precio_max, rating_min, pagina, por_pagina)
     * @return array Array con 'alojamientos' y 'total'
     */
    public function obtenerConFiltros($filtros = []) {
        $condiciones = [];
        $params = [];
        
        // Filtro de búsqueda por nombre o descripción
        if (!empty($filtros['busqueda'])) {
            $condiciones[] = "(name LIKE ? OR description LIKE ?)";
            $params[] = '%' . $filtros['busqueda'] . '%';
            $params[] = '%' . $filtros['busqueda'] . '%';
        }
        
        // Filtro por ubicación
        if (!empty($filtros['ubicacion'])) {
            $condiciones[] = "location LIKE ?";
            $params[] = '%' . $filtros['ubicacion'] . '%';
        }
        
        // Filtro por precio mínimo
        if (!empty($filtros['precio_min']) && is_numeric($filtros['precio_min'])) {
            $condiciones[] = "price_per_night >= ?";
            $params[] = $filtros['precio_min'];
        }
        
        // Filtro por precio máximo
        if (!empty($filtros['precio_max']) && is_numeric($filtros['precio_max'])) {
            $condiciones[] = "price_per_night <= ?";
            $params[] = $filtros['precio_max'];
        }
        
        // Filtro por calificación mínima
        if (!empty($filtros['rating_min']) && is_numeric($filtros['rating_min'])) {
            $condiciones[] = "rating >= ?";
            $params[] = $filtros['rating_min'];
        }
        
        // Construir WHERE clause
        $whereClause = !empty($condiciones) ? 'WHERE ' . implode(' AND ', $condiciones) : '';
        
        // Contar total de resultados
        $queryCount = "SELECT COUNT(*) as total FROM accommodations $whereClause";
        $resultCount = $this->db->selectOne($queryCount, $params);
        $total = $resultCount['total'];
        
        // Paginación
        $pagina = isset($filtros['pagina']) ? (int)$filtros['pagina'] : 1;
        $porPagina = isset($filtros['por_pagina']) ? (int)$filtros['por_pagina'] : 9;
        $offset = ($pagina - 1) * $porPagina;
        
        // Obtener alojamientos
        $query = "SELECT * FROM accommodations $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $porPagina;
        $params[] = $offset;
        
        $alojamientos = $this->db->select($query, $params);
        
        return [
            'alojamientos' => $alojamientos,
            'total' => $total
        ];
    }
    
    /**
     * Obtiene todas las ubicaciones únicas
     * 
     * @return array Lista de ubicaciones
     */
    public function obtenerUbicaciones() {
        $query = "SELECT DISTINCT location FROM accommodations ORDER BY location ASC";
        return $this->db->select($query);
    }
    
    /**
     * Obtiene todos los alojamientos disponibles
     * 
     * @return array Lista de todos los alojamientos
     */
    public function obtenerTodos() {
        $query = "SELECT * FROM accommodations ORDER BY created_at DESC";
        return $this->db->select($query);
    }
    
    /**
     * Busca un alojamiento por su ID
     * 
     * @param int $id ID del alojamiento
     * @return array|false Datos del alojamiento o false si no existe
     */
    public function buscarPorId($id) {
        $query = "SELECT * FROM accommodations WHERE id = ? LIMIT 1";
        return $this->db->selectOne($query, [$id]);
    }
    
    /**
     * Crea un nuevo alojamiento (solo para administradores)
     * 
     * @param string $name Nombre del alojamiento
     * @param string $description Descripción
     * @param string $imagePath URL de la imagen
     * @param string $location Ubicación
     * @param float $pricePerNight Precio por noche
     * @param float $rating Calificación (0.0 - 5.0)
     * @return bool True si se creó exitosamente, false en caso contrario
     */
    public function crear($name, $description, $imagePath, $location, $pricePerNight, $rating = 0.0) {
        $query = "
            INSERT INTO accommodations (name, description, image_path, location, price_per_night, rating) 
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        
        return $this->db->execute($query, [
            $name,
            $description,
            $imagePath,
            $location,
            $pricePerNight,
            $rating
        ]);
    }
    
    /**
     * Busca alojamientos por ubicación
     * 
     * @param string $location Ubicación a buscar
     * @return array Lista de alojamientos que coinciden
     */
    public function buscarPorUbicacion($location) {
        $query = "SELECT * FROM accommodations WHERE location LIKE ? ORDER BY rating DESC";
        return $this->db->select($query, ['%' . $location . '%']);
    }
    
    /**
     * Busca alojamientos por nombre
     * 
     * @param string $name Nombre a buscar
     * @return array Lista de alojamientos que coinciden
     */
    public function buscarPorNombre($name) {
        $query = "SELECT * FROM accommodations WHERE name LIKE ? ORDER BY rating DESC";
        return $this->db->select($query, ['%' . $name . '%']);
    }
    
    /**
     * Obtiene los alojamientos mejor valorados
     * 
     * @param int $limit Número máximo de resultados
     * @return array Lista de alojamientos mejor valorados
     */
    public function obtenerMejorValorados($limit = 10) {
        $query = "SELECT * FROM accommodations ORDER BY rating DESC, created_at DESC LIMIT ?";
        return $this->db->select($query, [$limit]);
    }
    
    /**
     * Obtiene los alojamientos más recientes
     * 
     * @param int $limit Número máximo de resultados
     * @return array Lista de alojamientos más recientes
     */
    public function obtenerRecientes($limit = 10) {
        $query = "SELECT * FROM accommodations ORDER BY created_at DESC LIMIT ?";
        return $this->db->select($query, [$limit]);
    }
    
    /**
     * Busca alojamientos dentro de un rango de precio
     * 
     * @param float $minPrice Precio mínimo
     * @param float $maxPrice Precio máximo
     * @return array Lista de alojamientos dentro del rango
     */
    public function buscarPorPrecio($minPrice, $maxPrice) {
        $query = "
            SELECT * FROM accommodations 
            WHERE price_per_night BETWEEN ? AND ? 
            ORDER BY price_per_night ASC
        ";
        return $this->db->select($query, [$minPrice, $maxPrice]);
    }
    
    /**
     * Cuenta el total de alojamientos
     * 
     * @return int Total de alojamientos
     */
    public function contarTotal() {
        $query = "SELECT COUNT(*) as total FROM accommodations";
        $result = $this->db->selectOne($query);
        return $result['total'];
    }
    
    /**
     * Actualiza la información de un alojamiento
     * 
     * @param int $id ID del alojamiento
     * @param string $name Nombre
     * @param string $description Descripción
     * @param string $imagePath URL de la imagen
     * @param string $location Ubicación
     * @param float $pricePerNight Precio por noche
     * @param float $rating Calificación
     * @return bool True si se actualizó exitosamente, false en caso contrario
     */
    public function actualizar($id, $name, $description, $imagePath, $location, $pricePerNight, $rating) {
        $query = "
            UPDATE accommodations 
            SET name = ?, description = ?, image_path = ?, location = ?, price_per_night = ?, rating = ?
            WHERE id = ?
        ";
        
        return $this->db->execute($query, [
            $name,
            $description,
            $imagePath,
            $location,
            $pricePerNight,
            $rating,
            $id
        ]);
    }
    
    /**
     * Elimina un alojamiento
     * 
     * @param int $id ID del alojamiento
     * @return bool True si se eliminó exitosamente, false en caso contrario
     */
    public function eliminar($id) {
        $query = "DELETE FROM accommodations WHERE id = ?";
        return $this->db->execute($query, [$id]);
    }
    
    /**
     * Obtiene estadísticas de alojamientos
     * 
     * @return array Estadísticas (total, precio promedio, mejor valorado)
     */
    public function obtenerEstadisticas() {
        $query = "
            SELECT 
                COUNT(*) as total,
                AVG(price_per_night) as precio_promedio,
                MAX(rating) as mejor_rating,
                MIN(price_per_night) as precio_minimo,
                MAX(price_per_night) as precio_maximo
            FROM accommodations
        ";
        return $this->db->selectOne($query);
    }
}
