# 📚 Documentación Técnica - AlojaTEC

## 🏗️ Arquitectura del Proyecto

AlojaTEC está construido siguiendo el patrón **MVC (Modelo-Vista-Controlador)** en PHP puro.

---

## 📂 Estructura de Carpetas Detallada

```
CRUD/
│
├── app/                          # Lógica de la aplicación
│   ├── Controllers/              # Controladores (lógica de negocio)
│   │   ├── AccommodationController.php
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   └── UserController.php
│   │
│   ├── Models/                   # Modelos (interacción con BD)
│   │   ├── Accommodation.php
│   │   └── User.php
│   │
│   ├── Views/                    # Vistas (interfaz de usuario)
│   │   ├── accommodations/       # Vistas de alojamientos
│   │   ├── admin/                # Vistas del administrador
│   │   ├── auth/                 # Login y registro
│   │   ├── errors/               # Páginas de error
│   │   ├── home/                 # Página principal
│   │   ├── layouts/              # Layout base
│   │   │   └── main.php
│   │   └── user/                 # Vistas de usuario
│   │
│   └── Database.php              # Clase de conexión PDO
│
├── config/                       # Configuraciones
│   └── database.php              # Config de base de datos
│
├── public/                       # Archivos públicos
│   ├── css/
│   │   └── styles.css            # Estilos CSS
│   ├── images/                   # Imágenes locales
│   └── js/
│       └── app.js                # JavaScript
│
├── .htaccess                     # Configuración de Apache
├── database.sql                  # Script de base de datos
├── index.php                     # Front Controller (router)
├── README.md                     # Documentación general
├── INSTALACION.md                # Guía de instalación
└── DOCUMENTACION.md              # Este archivo
```

---

## 🎯 Flujo de Datos (MVC)

```
Usuario → index.php (Router) → Controlador → Modelo → Base de Datos
                                     ↓
                                   Vista → Usuario
```

### Ejemplo: Ver alojamientos

1. **Usuario** visita: `http://localhost/CRUD/`
2. **index.php** recibe la petición y enruta a `AccommodationController::index()`
3. **Controlador** llama a `Accommodation::obtenerTodos()`
4. **Modelo** consulta la base de datos con PDO
5. **Controlador** pasa los datos a la vista `Views/home/index.php`
6. **Vista** renderiza HTML con los alojamientos
7. **Usuario** ve la página con los alojamientos

---

## 🔌 Clase Database (Singleton)

### Ubicación: `app/Database.php`

Implementa el patrón Singleton para tener una única instancia de conexión PDO.

### Métodos Principales:

```php
// Obtener instancia única
$db = Database::getInstance();

// Ejecutar SELECT (múltiples resultados)
$results = $db->select($query, $params);

// Ejecutar SELECT (un solo resultado)
$result = $db->selectOne($query, $params);

// Ejecutar INSERT/UPDATE/DELETE
$success = $db->execute($query, $params);

// Obtener ID del último insert
$lastId = $db->lastInsertId();
```

### Ejemplo de Uso:

```php
$db = Database::getInstance();
$users = $db->select("SELECT * FROM users WHERE role = ?", ['user']);
```

---

## 🎮 Controladores

### AuthController

**Responsabilidad:** Gestión de autenticación

**Métodos:**
- `mostrarLogin()` - Muestra formulario de login
- `procesarLogin()` - Procesa credenciales
- `mostrarRegistro()` - Muestra formulario de registro
- `procesarRegistro()` - Crea nuevo usuario
- `logout()` - Cierra sesión
- `estaAutenticado()` - Verifica si hay sesión activa
- `esAdmin()` - Verifica si el usuario es admin
- `requiereAuth()` - Middleware para rutas protegidas
- `requiereAdmin()` - Middleware para rutas de admin

### AccommodationController

**Responsabilidad:** Gestión de alojamientos

**Métodos:**
- `index()` - Lista todos los alojamientos (landing page)
- `agregarFavorito()` - Agrega alojamiento a favoritos
- `eliminarFavorito()` - Elimina alojamiento de favoritos
- `mostrarDetalle($id)` - Muestra detalles de un alojamiento
- `buscar()` - Busca alojamientos por criterios

### UserController

**Responsabilidad:** Panel de usuario

**Métodos:**
- `miCuenta()` - Muestra favoritos del usuario
- `editarPerfil()` - Formulario de edición
- `actualizarPerfil()` - Procesa actualización
- `cambiarPassword()` - Formulario de cambio de contraseña
- `procesarCambioPassword()` - Procesa cambio

### AdminController

**Responsabilidad:** Panel de administración

**Métodos:**
- `index()` - Dashboard con estadísticas
- `agregarAlojamiento()` - Formulario de nuevo alojamiento
- `procesarAgregarAlojamiento()` - Crea nuevo alojamiento
- `estadisticas()` - Muestra estadísticas detalladas

---

## 📊 Modelos

### User

**Responsabilidad:** Operaciones relacionadas con usuarios

**Métodos principales:**

```php
// Crear nuevo usuario
public function crear($name, $email, $password, $role = 'user'): bool

// Buscar usuario por email
public function buscarPorEmail($email): array|false

// Verificar credenciales de login
public function verificarCredenciales($email, $password): array|false

// Obtener favoritos del usuario
public function obtenerFavoritos($userId): array

// Agregar a favoritos
public function agregarFavorito($userId, $accommodationId): bool

// Eliminar de favoritos
public function eliminarFavorito($userId, $accommodationId): bool

// Verificar si es favorito
public function esFavorito($userId, $accommodationId): bool
```

### Accommodation

**Responsabilidad:** Operaciones relacionadas con alojamientos

**Métodos principales:**

```php
// Obtener todos los alojamientos
public function obtenerTodos(): array

// Buscar por ID
public function buscarPorId($id): array|false

// Crear nuevo alojamiento
public function crear($name, $description, $imagePath, $location, $pricePerNight, $rating): bool

// Buscar por ubicación
public function buscarPorUbicacion($location): array

// Obtener mejor valorados
public function obtenerMejorValorados($limit = 10): array

// Obtener estadísticas
public function obtenerEstadisticas(): array
```

---

## 🛣️ Sistema de Rutas

### Ubicación: `index.php`

El archivo `index.php` actúa como **Front Controller**, enrutando todas las solicitudes.

### Definición de Rutas:

```php
$routes = [
    '/' => ['controller' => 'AccommodationController', 'method' => 'index'],
    '/login' => ['controller' => 'AuthController', 'method' => 'mostrarLogin'],
    '/registro' => ['controller' => 'AuthController', 'method' => 'mostrarRegistro'],
    // ... más rutas
];
```

### Proceso:

1. `.htaccess` redirige todas las peticiones a `index.php`
2. `index.php` parsea la URI
3. Busca la ruta en el array `$routes`
4. Instancia el controlador correspondiente
5. Llama al método especificado

---

## 🔐 Seguridad

### 1. Contraseñas

- **Hash:** `password_hash($password, PASSWORD_DEFAULT)`
- **Verificación:** `password_verify($password, $hash)`

### 2. SQL Injection

- **PDO Prepared Statements:** Todos los queries usan placeholders `?`

```php
$query = "SELECT * FROM users WHERE email = ?";
$db->select($query, [$email]);
```

### 3. XSS (Cross-Site Scripting)

- **Sanitización:** `htmlspecialchars()` en todas las salidas

```php
<?= htmlspecialchars($usuario['name']) ?>
```

### 4. Control de Acceso

- **Middleware:** `requiereAuth()` y `requiereAdmin()`
- **Sesiones:** Variables `$_SESSION['user_id']` y `$_SESSION['user_role']`

---

## 🎨 Sistema de Vistas

### Layout Principal: `layouts/main.php`

Todas las vistas usan este layout base que incluye:
- Navbar
- Sistema de alertas
- Footer
- Enlaces a CSS y JS

### Uso en las Vistas:

```php
<?php
$titulo = 'Mi Página';
ob_start();
?>

<!-- Contenido HTML aquí -->

<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>
```

---

## 🎨 Estilos CSS

### Variables CSS Personalizadas:

```css
:root {
  --dark-blue-50: #f0efff;
  --dark-blue-500: #6b4fff;  /* Primario */
  --dark-blue-700: #4000d6;  /* Hover */
  --dark-blue-900: #1b0069;  /* Texto */
}
```

### Clases Principales:

- `.btn` - Botones
- `.btn-primary` - Botón primario
- `.accommodation-card` - Tarjeta de alojamiento
- `.form-container` - Contenedor de formularios
- `.alert` - Alertas (success, error, info)
- `.dashboard` - Panel de usuario/admin

---

## 💾 Base de Datos

### Tabla: users

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Tabla: accommodations

```sql
CREATE TABLE accommodations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    location VARCHAR(150) NOT NULL,
    price_per_night DECIMAL(10, 2) NOT NULL,
    rating DECIMAL(2, 1) DEFAULT 0.0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Tabla: user_favorites

```sql
CREATE TABLE user_favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    accommodation_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (accommodation_id) REFERENCES accommodations(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, accommodation_id)
);
```

---

## 🔄 Sesiones

### Variables de Sesión:

```php
$_SESSION['user_id']          // ID del usuario
$_SESSION['user_name']        // Nombre del usuario
$_SESSION['user_email']       // Email del usuario
$_SESSION['user_role']        // Rol: 'user' o 'admin'
$_SESSION['success']          // Mensaje de éxito
$_SESSION['errores']          // Array de errores
$_SESSION['old_input']        // Datos del formulario anterior
```

---

## 🚀 Extensiones Futuras

### Backend:
- Implementar edición y eliminación de alojamientos
- Sistema de reservas
- API REST
- Sistema de roles más complejo
- Paginación

### Frontend:
- Sistema de búsqueda en tiempo real
- Filtros avanzados
- Modo oscuro
- Mejoras responsive
- Animaciones adicionales

---

## 📝 Convenciones de Código

### PHP:
- **Nombres de clases:** PascalCase (`UserController`)
- **Nombres de métodos:** camelCase (`obtenerTodos()`)
- **Nombres de variables:** snake_case o camelCase
- **Comentarios:** PHPDoc para clases y métodos

### SQL:
- **Tablas:** snake_case plural (`users`, `accommodations`)
- **Columnas:** snake_case (`created_at`, `price_per_night`)

### CSS:
- **Clases:** kebab-case (`.accommodation-card`)
- **Variables:** kebab-case (`--dark-blue-500`)

---

**Documentación creada para AlojaTEC v1.0**
