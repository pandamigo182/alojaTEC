<?php
/**
 * Modelo Review
 * 
 * Maneja toda la lógica relacionada con las reseñas y comentarios
 * de los alojamientos.
 */

require_once __DIR__ . '/../Database.php';

class Review {
    
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
     * Crea una nueva reseña
     * 
     * @param int $userId ID del usuario
     * @param int $accommodationId ID del alojamiento
     * @param int $rating Calificación (1-5)
     * @param string $comment Comentario
     * @return bool True si se creó exitosamente, false en caso contrario
     */
    public function crear($userId, $accommodationId, $rating, $comment) {
        $query = "INSERT INTO reviews (user_id, accommodation_id, rating, comment) VALUES (?, ?, ?, ?)";
        return $this->db->execute($query, [$userId, $accommodationId, $rating, $comment]);
    }
    
    /**
     * Obtiene todas las reseñas de un alojamiento
     * 
     * @param int $accommodationId ID del alojamiento
     * @return array Lista de reseñas con información del usuario
     */
    public function obtenerPorAlojamiento($accommodationId) {
        $query = "
            SELECT r.*, u.name as user_name
            FROM reviews r
            INNER JOIN users u ON r.user_id = u.id
            WHERE r.accommodation_id = ?
            ORDER BY r.created_at DESC
        ";
        
        return $this->db->select($query, [$accommodationId]);
    }
    
    /**
     * Obtiene todas las reseñas de un usuario
     * 
     * @param int $userId ID del usuario
     * @return array Lista de reseñas del usuario
     */
    public function obtenerPorUsuario($userId) {
        $query = "
            SELECT r.*, a.name as accommodation_name
            FROM reviews r
            INNER JOIN accommodations a ON r.accommodation_id = a.id
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC
        ";
        
        return $this->db->select($query, [$userId]);
    }
    
    /**
     * Verifica si un usuario ya ha comentado en un alojamiento
     * 
     * @param int $userId ID del usuario
     * @param int $accommodationId ID del alojamiento
     * @return bool True si ya comentó, false en caso contrario
     */
    public function usuarioYaComento($userId, $accommodationId) {
        $query = "SELECT COUNT(*) as total FROM reviews WHERE user_id = ? AND accommodation_id = ?";
        $result = $this->db->selectOne($query, [$userId, $accommodationId]);
        return $result['total'] > 0;
    }
    
    /**
     * Actualiza una reseña
     * 
     * @param int $id ID de la reseña
     * @param int $rating Nueva calificación
     * @param string $comment Nuevo comentario
     * @return bool True si se actualizó exitosamente, false en caso contrario
     */
    public function actualizar($id, $rating, $comment) {
        $query = "UPDATE reviews SET rating = ?, comment = ? WHERE id = ?";
        return $this->db->execute($query, [$rating, $comment, $id]);
    }
    
    /**
     * Elimina una reseña
     * 
     * @param int $id ID de la reseña
     * @return bool True si se eliminó exitosamente, false en caso contrario
     */
    public function eliminar($id) {
        $query = "DELETE FROM reviews WHERE id = ?";
        return $this->db->execute($query, [$id]);
    }
    
    /**
     * Obtiene el promedio de calificaciones de un alojamiento
     * 
     * @param int $accommodationId ID del alojamiento
     * @return float Promedio de calificaciones
     */
    public function obtenerPromedioCalificacion($accommodationId) {
        $query = "SELECT AVG(rating) as promedio FROM reviews WHERE accommodation_id = ?";
        $result = $this->db->selectOne($query, [$accommodationId]);
        return ($result && isset($result['promedio']) && $result['promedio']) ? round($result['promedio'], 1) : 0;
    }
    
    /**
     * Obtiene el número total de reseñas de un alojamiento
     * 
     * @param int $accommodationId ID del alojamiento
     * @return int Total de reseñas
     */
    public function contarReseñas($accommodationId) {
        $query = "SELECT COUNT(*) as total FROM reviews WHERE accommodation_id = ?";
        $result = $this->db->selectOne($query, [$accommodationId]);
        return ($result && isset($result['total'])) ? $result['total'] : 0;
    }
    
    /**
     * Busca una reseña por su ID
     * 
     * @param int $id ID de la reseña
     * @return array|false Datos de la reseña o false si no existe
     */
    public function buscarPorId($id) {
        $query = "SELECT * FROM reviews WHERE id = ? LIMIT 1";
        return $this->db->selectOne($query, [$id]);
    }
}
