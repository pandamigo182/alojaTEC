<?php
/**
 * Modelo User
 * 
 * Maneja toda la lógica relacionada con los usuarios,
 * incluyendo autenticación, registro y gestión de favoritos.
 */

require_once __DIR__ . '/../Database.php';

class User {
    
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
     * Crea un nuevo usuario en la base de datos
     * 
     * @param string $name Nombre del usuario
     * @param string $email Correo electrónico
     * @param string $password Contraseña (será hasheada)
     * @param string $role Rol del usuario ('user' o 'admin')
     * @return bool True si se creó exitosamente, false en caso contrario
     */
    public function crear($name, $email, $password, $role = 'user') {
        // Hashear la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Preparar consulta
        $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        
        return $this->db->execute($query, [$name, $email, $passwordHash, $role]);
    }
    
    /**
     * Busca un usuario por su correo electrónico
     * 
     * @param string $email Correo electrónico
     * @return array|false Datos del usuario o false si no existe
     */
    public function buscarPorEmail($email) {
        $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
        return $this->db->selectOne($query, [$email]);
    }
    
    /**
     * Busca un usuario por su ID
     * 
     * @param int $id ID del usuario
     * @return array|false Datos del usuario o false si no existe
     */
    public function buscarPorId($id) {
        $query = "SELECT * FROM users WHERE id = ? LIMIT 1";
        return $this->db->selectOne($query, [$id]);
    }
    
    /**
     * Verifica las credenciales de login
     * 
     * @param string $email Correo electrónico
     * @param string $password Contraseña
     * @return array|false Datos del usuario si las credenciales son correctas, false en caso contrario
     */
    public function verificarCredenciales($email, $password) {
        $usuario = $this->buscarPorEmail($email);
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        
        return false;
    }
    
    /**
     * Verifica si un email ya está registrado
     * 
     * @param string $email Correo electrónico
     * @return bool True si el email existe, false en caso contrario
     */
    public function emailExiste($email) {
        $query = "SELECT COUNT(*) as total FROM users WHERE email = ?";
        $result = $this->db->selectOne($query, [$email]);
        return $result['total'] > 0;
    }
    
    /**
     * Obtiene todos los alojamientos favoritos de un usuario
     * 
     * @param int $userId ID del usuario
     * @return array Lista de alojamientos favoritos
     */
    public function obtenerFavoritos($userId) {
        $query = "
            SELECT a.*, uf.created_at as fecha_agregado
            FROM accommodations a
            INNER JOIN user_favorites uf ON a.id = uf.accommodation_id
            WHERE uf.user_id = ?
            ORDER BY uf.created_at DESC
        ";
        
        return $this->db->select($query, [$userId]);
    }
    
    /**
     * Agrega un alojamiento a los favoritos del usuario
     * 
     * @param int $userId ID del usuario
     * @param int $accommodationId ID del alojamiento
     * @return bool True si se agregó exitosamente, false en caso contrario
     */
    public function agregarFavorito($userId, $accommodationId) {
        // Verificar si ya existe en favoritos
        if ($this->esFavorito($userId, $accommodationId)) {
            return false;
        }
        
        $query = "INSERT INTO user_favorites (user_id, accommodation_id) VALUES (?, ?)";
        return $this->db->execute($query, [$userId, $accommodationId]);
    }
    
    /**
     * Elimina un alojamiento de los favoritos del usuario
     * 
     * @param int $userId ID del usuario
     * @param int $accommodationId ID del alojamiento
     * @return bool True si se eliminó exitosamente, false en caso contrario
     */
    public function eliminarFavorito($userId, $accommodationId) {
        $query = "DELETE FROM user_favorites WHERE user_id = ? AND accommodation_id = ?";
        return $this->db->execute($query, [$userId, $accommodationId]);
    }
    
    /**
     * Verifica si un alojamiento está en los favoritos del usuario
     * 
     * @param int $userId ID del usuario
     * @param int $accommodationId ID del alojamiento
     * @return bool True si es favorito, false en caso contrario
     */
    public function esFavorito($userId, $accommodationId) {
        $query = "SELECT COUNT(*) as total FROM user_favorites WHERE user_id = ? AND accommodation_id = ?";
        $result = $this->db->selectOne($query, [$userId, $accommodationId]);
        return $result['total'] > 0;
    }
    
    /**
     * Actualiza el perfil de un usuario
     * 
     * @param int $id ID del usuario
     * @param string $name Nuevo nombre
     * @param string $email Nuevo email
     * @return bool True si se actualizó exitosamente, false en caso contrario
     */
    public function actualizar($id, $name, $email) {
        $query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        return $this->db->execute($query, [$name, $email, $id]);
    }
    
    /**
     * Cambia la contraseña de un usuario
     * 
     * @param int $id ID del usuario
     * @param string $newPassword Nueva contraseña
     * @return bool True si se cambió exitosamente, false en caso contrario
     */
    public function cambiarPassword($id, $newPassword) {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ? WHERE id = ?";
        return $this->db->execute($query, [$passwordHash, $id]);
    }
}
