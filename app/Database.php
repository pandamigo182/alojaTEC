<?php
/**
 * Clase Database
 * 
 * Maneja la conexión a la base de datos usando PDO
 * y proporciona métodos para ejecutar consultas de forma segura.
 */

class Database {
    
    /**
     * Instancia única de PDO (patrón Singleton)
     * @var PDO|null
     */
    private static $instance = null;
    
    /**
     * Objeto de conexión PDO
     * @var PDO
     */
    private $connection;
    
    /**
     * Constructor privado para implementar Singleton
     * Establece la conexión con la base de datos
     */
    private function __construct() {
        try {
            // Cargar configuración de la base de datos
            $config = require __DIR__ . '/../config/database.php';
            
            // Crear DSN (Data Source Name)
            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
            
            // Establecer conexión PDO
            $this->connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
            
        } catch (PDOException $e) {
            // Registrar error y mostrar mensaje amigable
            error_log("Error de conexión: " . $e->getMessage());
            die("No se pudo conectar a la base de datos. Por favor, intenta más tarde.");
        }
    }
    
    /**
     * Obtiene la instancia única de la clase Database (Singleton)
     * 
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Obtiene el objeto de conexión PDO
     * 
     * @return PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Ejecuta una consulta SELECT y devuelve todos los resultados
     * 
     * @param string $query Consulta SQL
     * @param array $params Parámetros para prepared statement
     * @return array Resultados de la consulta
     */
    public function select($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en SELECT: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Ejecuta una consulta SELECT y devuelve un solo resultado
     * 
     * @param string $query Consulta SQL
     * @param array $params Parámetros para prepared statement
     * @return array|false Resultado de la consulta o false si no hay resultado
     */
    public function selectOne($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error en SELECT ONE: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Ejecuta una consulta INSERT, UPDATE o DELETE
     * 
     * @param string $query Consulta SQL
     * @param array $params Parámetros para prepared statement
     * @return bool True si la consulta fue exitosa, false en caso contrario
     */
    public function execute($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error en EXECUTE: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene el ID del último registro insertado
     * 
     * @return string ID del último insert
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Inicia una transacción
     * 
     * @return bool
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Confirma una transacción
     * 
     * @return bool
     */
    public function commit() {
        return $this->connection->commit();
    }
    
    /**
     * Revierte una transacción
     * 
     * @return bool
     */
    public function rollback() {
        return $this->connection->rollback();
    }
    
    /**
     * Prevenir la clonación de la instancia
     */
    private function __clone() {}
    
    /**
     * Prevenir la deserialización de la instancia
     */
    public function __wakeup() {
        throw new Exception("No se puede deserializar un singleton.");
    }
}
