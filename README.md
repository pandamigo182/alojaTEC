# 🏡 AlojaTEC - Plataforma de Gestión de Alojamientos

![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange)
![License](https://img.shields.io/badge/License-MIT-green)

## 📋 Descripción del Proyecto

**AlojaTEC** es una aplicación web desarrollada en PHP 8 puro con arquitectura MVC para gestionar alojamientos. Los usuarios pueden registrarse, explorar alojamientos y guardar sus favoritos. Los administradores tienen la capacidad de agregar nuevos alojamientos a la plataforma.

**Desarrollado por:** Edwin Efrain Juárez Mezquita  
**Rol:** Full Stack Jr - 31  
**Proyecto:** Final de Curso

---

## ✨ Características Principales

### Para Usuarios:
- ✅ Registro y autenticación de usuarios
- 🏠 Exploración de catálogo de alojamientos
- ❤️ Sistema de favoritos personalizado
- 👤 Panel de cuenta con gestión de favoritos
- 🔒 Edición de perfil y cambio de contraseña

### Para Administradores:
- ➕ Agregar nuevos alojamientos
- 📊 Dashboard con estadísticas
- 📋 Listado completo de alojamientos

---

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 8 (POO, MVC)
- **Base de Datos:** MySQL
- **Frontend:** HTML5, CSS3 (variables CSS personalizadas)
- **Iconos:** Font Awesome 6.4
- **Servidor:** Apache (XAMPP/Laragon)

---

## 📦 Instalación

### Requisitos Previos
- PHP 8.0 o superior
- MySQL 5.7 o superior
- Apache (con mod_rewrite habilitado)
- XAMPP, WAMP o Laragon

### Paso 1: Clonar/Descargar el Proyecto

Coloca el proyecto en la carpeta `htdocs` de XAMPP:
```
C:\xampp\htdocs\CRUD\
```

### Paso 2: Configurar la Base de Datos

1. Abre **phpMyAdmin** en tu navegador: `http://localhost/phpmyadmin`

2. Ejecuta el archivo SQL ubicado en `database.sql` para crear:
   - Base de datos: `alojatec_db`
   - Tablas: `users`, `accommodations`, `user_favorites`
   - Datos de prueba con 12 alojamientos precargados
   - Usuario administrador predeterminado

### Paso 3: Verificar Configuración

Abre el archivo `config/database.php` y verifica las credenciales:

```php
return [
    'host' => 'localhost',
    'database' => 'alojatec_db',
    'username' => 'root',
    'password' => '',  // Modifica si tienes contraseña en MySQL
];
```

### Paso 4: Configurar Apache

Asegúrate de que el módulo `mod_rewrite` de Apache esté habilitado.

En XAMPP, el archivo `.htaccess` ya está configurado correctamente.

### Paso 5: Acceder a la Aplicación

Abre tu navegador y visita:
```
http://localhost/alojatec/
```

---

## 👥 Credenciales de Acceso

### Administrador Predeterminado:
- **Email:** admin@alojatec.com
- **Contraseña:** admin123

### Crear Usuario Normal:
Usa el formulario de registro en: `http://localhost/alojatec/registro`

---

## 📂 Estructura del Proyecto

```
alojatec/
├── app/
│   ├── Controllers/          # Controladores MVC
│   │   ├── AccommodationController.php
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   └── UserController.php
│   ├── Models/              # Modelos de datos
│   │   ├── Accommodation.php
│   │   └── User.php
│   ├── Views/               # Vistas HTML/PHP
│   │   ├── accommodations/
│   │   ├── admin/
│   │   ├── auth/
│   │   ├── errors/
│   │   ├── home/
│   │   ├── layouts/
│   │   └── user/
│   └── Database.php         # Clase de conexión DB
├── config/
│   └── database.php         # Configuración DB
├── public/
│   ├── css/
│   │   └── styles.css       # Estilos personalizados
│   ├── images/              # Imágenes locales
│   └── js/                  # Scripts JS (futuro)
├── .htaccess                # Configuración Apache
├── database.sql             # Script de base de datos
├── index.php                # Front Controller
└── README.md                # Este archivo
```

---

## 🎨 Paleta de Colores

El proyecto utiliza una paleta de colores morada/azul definida en CSS:

```css
:root {
  --dark-blue-500: #6b4fff;  /* Color primario */
  --dark-blue-700: #4000d6;  /* Hover */
  --dark-blue-900: #1b0069;  /* Texto oscuro */
}
```

---

## 🚀 Funcionalidades por Rol

### 🔹 Usuario Normal
1. **Registro:** Crear una cuenta nueva
2. **Login:** Iniciar sesión
3. **Explorar:** Ver todos los alojamientos disponibles
4. **Favoritos:** Agregar/eliminar alojamientos de su lista personal
5. **Perfil:** Editar nombre, email y cambiar contraseña

### 🔹 Administrador
1. **Dashboard:** Ver estadísticas de alojamientos
2. **Agregar:** Crear nuevos alojamientos con:
   - Nombre
   - Descripción
   - Imagen (URL)
   - Ubicación
   - Precio por noche
   - Calificación
3. **Listar:** Ver todos los alojamientos en tabla

---

## 🔐 Seguridad Implementada

- ✅ Contraseñas hasheadas con `password_hash()` y `PASSWORD_DEFAULT`
- ✅ Prepared statements (PDO) para prevenir SQL Injection
- ✅ Validación de datos en servidor
- ✅ Sanitización de salidas con `htmlspecialchars()`
- ✅ Protección de archivos sensibles en `.htaccess`
- ✅ Control de acceso basado en roles

---

## 📱 Rutas de la Aplicación

| Ruta | Descripción | Acceso |
|------|-------------|--------|
| `/` | Landing page con alojamientos | Público |
| `/login` | Formulario de inicio de sesión | Público |
| `/registro` | Formulario de registro | Público |
| `/mi-cuenta` | Panel de usuario con favoritos | Usuario autenticado |
| `/editar-perfil` | Editar información personal | Usuario autenticado |
| `/admin` | Panel de administración | Solo Admin |
| `/admin/agregar-alojamiento` | Agregar nuevo alojamiento | Solo Admin |
| `/logout` | Cerrar sesión | Autenticado |

---

## 🐛 Solución de Problemas

### Problema: Error 404 en todas las rutas
**Solución:** Verifica que `mod_rewrite` esté habilitado en Apache.

### Problema: Error de conexión a la base de datos
**Solución:** 
1. Verifica que MySQL esté corriendo
2. Confirma las credenciales en `config/database.php`
3. Asegúrate de haber ejecutado `database.sql`

### Problema: Los estilos CSS no cargan
**Solución:** Verifica la ruta en `layouts/main.php`:
```php
<link rel="stylesheet" href="/alojatec/public/css/styles.css">
```

---

## 📝 Base de Datos

### Tablas Principales:

**users**
- id, name, email, password, role, created_at, updated_at

**accommodations**
- id, name, description, image_path, location, price_per_night, rating, created_at, updated_at

**user_favorites**
- id, user_id, accommodation_id, created_at

---

## 🎯 Próximas Mejoras

- [ ] Sistema de búsqueda y filtros
- [ ] Paginación de alojamientos
- [ ] Subida de imágenes locales
- [ ] Edición y eliminación de alojamientos (Admin)
- [ ] Sistema de comentarios y reseñas
- [ ] Modo oscuro
- [ ] Responsive mejorado para móviles
- [ ] API REST para consumo externo

---

## 👨‍💻 Autor

**Edwin Efrain Juárez Mezquita**  
Full Stack Developer Jr  

---

## 📄 Licencia

Este proyecto fue creado con fines educativos como proyecto final de curso.

---

## 🙏 Agradecimientos

- Imágenes de alojamientos: [Unsplash](https://unsplash.com)
- Iconos: [Font Awesome](https://fontawesome.com)
- Inspiración de diseño: Plataformas de reserva modernas

---

**¡Gracias por revisar AlojaTEC! 🏡✨**

---

## 🔒 Nota de seguridad

Se eliminaron del repositorio los scripts auxiliares usados durante el desarrollo (por ejemplo para crear usuarios de prueba o resetear contraseñas). Para gestionar datos de prueba o cuentas utiliza `database.sql` y herramientas como phpMyAdmin en tu entorno local. Estos scripts no deben usarse en producción.
