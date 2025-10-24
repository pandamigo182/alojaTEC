# 📖 Guía de Instalación Paso a Paso - AlojaTEC

Esta guía te ayudará a instalar y configurar AlojaTEC en tu entorno local.

---

## 🎯 Paso 1: Verificar Requisitos

Antes de comenzar, asegúrate de tener instalado:

- ✅ **XAMPP** (o WAMP/Laragon) con:
  - PHP 8.0 o superior
  - MySQL 5.7 o superior
  - Apache con mod_rewrite habilitado

### Verificar versión de PHP:
1. Abre XAMPP Control Panel
2. Haz clic en "Shell"
3. Escribe: `php -v`
4. Deberías ver algo como: `PHP 8.0.x` o superior

---

## 🎯 Paso 2: Colocar el Proyecto

1. **Copia la carpeta del proyecto** a la carpeta `htdocs` de XAMPP:
   ```
   C:\xampp\htdocs\alojatec\
   ```

2. **Verifica que la estructura sea correcta:**
   ```
   C:\xampp\htdocs\alojatec\
   ├── app/
   ├── config/
   ├── public/
   ├── index.php
   ├── .htaccess
   └── database.sql
   ```

---

## 🎯 Paso 3: Iniciar Servicios

1. **Abre XAMPP Control Panel**

2. **Inicia Apache:**
   - Haz clic en "Start" junto a Apache
   - El botón debe volverse verde

3. **Inicia MySQL:**
   - Haz clic en "Start" junto a MySQL
   - El botón debe volverse verde

![XAMPP Control Panel](https://via.placeholder.com/600x200?text=XAMPP+Control+Panel)

---

## 🎯 Paso 4: Crear la Base de Datos

### Opción A: Usando phpMyAdmin (Recomendado)

1. **Abre tu navegador** y ve a:
   ```
   http://localhost/phpmyadmin
   ```

2. **Importar el archivo SQL:**
   - Haz clic en la pestaña "Importar"
   - Haz clic en "Seleccionar archivo"
   - Navega a: `C:\xampp\htdocs\CRUD\database.sql`
   - Haz clic en "Continuar" al final de la página

3. **Verificar:**
   - En el panel izquierdo deberías ver: `alojatec_db`
   - Haz clic en ella y verifica que tenga 3 tablas:
     - `users`
     - `accommodations`
     - `user_favorites`

### Opción B: Usando la línea de comandos

1. Abre XAMPP Shell
2. Ejecuta:
   ```bash
   mysql -u root -p < C:\xampp\htdocs\alojatec\database.sql
   ```
3. Presiona Enter (sin contraseña si es instalación por defecto)

---

## 🎯 Paso 5: Configurar la Conexión a la Base de Datos

1. **Abre el archivo:**
   ```
   C:\xampp\htdocs\alojatec\config\database.php
   ```

2. **Verifica los datos de conexión:**
   ```php
   return [
       'host' => 'localhost',
       'database' => 'alojatec_db',
       'username' => 'root',
       'password' => '',  // Déjalo vacío si no configuraste contraseña
   ];
   ```

3. **Si tu MySQL tiene contraseña**, cámbiala en el campo `password`

---

## 🎯 Paso 6: Verificar mod_rewrite

El proyecto usa URLs amigables, por lo que necesitas `mod_rewrite` habilitado.

### Verificar si está habilitado:

1. **Abre:**
   ```
   C:\xampp\apache\conf\httpd.conf
   ```

2. **Busca la línea:**
   ```apache
   #LoadModule rewrite_module modules/mod_rewrite.so
   ```

3. **Si tiene un `#` al inicio, quítalo:**
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```

4. **Guarda el archivo y reinicia Apache** desde XAMPP Control Panel

---

## 🎯 Paso 7: Probar la Aplicación

1. **Abre tu navegador**

2. **Visita:**
   ```
   http://localhost/alojatec/
   ```

3. **Deberías ver:**
   - La página principal de AlojaTEC
   - Un hero section con título "Bienvenido a AlojaTEC"
   - 12 tarjetas de alojamientos cargados desde la base de datos

---

## 🎯 Paso 8: Iniciar Sesión como Administrador

1. **Haz clic en "Iniciar Sesión"** en el menú superior

2. **Usa estas credenciales:**
   - **Email:** `admin@alojatec.com`
   - **Contraseña:** `admin123`

3. **Después de iniciar sesión:**
   - Deberías ser redirigido al panel de administración
   - Verás estadísticas de los alojamientos
   - Podrás agregar nuevos alojamientos

---

## 🎯 Paso 9: Crear un Usuario Normal

1. **Cierra sesión** (haz clic en "Cerrar Sesión")

2. **Haz clic en "Registrarse"**

3. **Completa el formulario:**
   - Nombre: Tu nombre
   - Email: tu@email.com
   - Contraseña: mínimo 6 caracteres
   - Confirmar contraseña

4. **Después del registro:**
   - Serás redirigido al login
   - Inicia sesión con tu nuevo usuario
   - Podrás agregar alojamientos a favoritos

---

## 🎯 Paso 10: Explorar Funcionalidades

### Como Usuario Normal:
- ✅ Ver todos los alojamientos en la página principal
- ✅ Agregar alojamientos a favoritos
- ✅ Ver tus favoritos en "Mi Cuenta"
- ✅ Eliminar favoritos
- ✅ Editar tu perfil
- ✅ Cambiar tu contraseña

### Como Administrador:
- ✅ Ver estadísticas en el dashboard
- ✅ Agregar nuevos alojamientos
- ✅ Ver listado completo de alojamientos

---

## ❗ Solución de Problemas Comunes

### Problema: "Error de conexión a la base de datos"

**Solución:**
1. Verifica que MySQL esté corriendo en XAMPP
2. Confirma que ejecutaste el archivo `database.sql`
3. Revisa las credenciales en `config/database.php`

---

### Problema: "404 - Página no encontrada" en todas las rutas

**Solución:**
1. Verifica que `mod_rewrite` esté habilitado (ver Paso 6)
2. Confirma que el archivo `.htaccess` existe en la raíz del proyecto
3. Reinicia Apache

---

### Problema: Los estilos CSS no cargan

**Solución:**
1. Verifica que la carpeta `public/css/styles.css` exista
2. Abre el navegador y ve a: `http://localhost/alojatec/public/css/styles.css`
3. Deberías ver el código CSS. Si ves un error 404, revisa la estructura de carpetas

---

### Problema: Las imágenes no cargan

**Causa:** Las imágenes están alojadas en URLs externas (Unsplash)

**Solución:**
1. Verifica tu conexión a internet
2. Si persiste, puedes cambiar las URLs en la base de datos por imágenes locales

---

## 🎉 ¡Listo!

Si todo funciona correctamente, deberías poder:

✅ Ver la landing page con alojamientos  
✅ Registrarte como nuevo usuario  
✅ Iniciar sesión  
✅ Agregar favoritos  
✅ Acceder al panel de administración (como admin)  
✅ Agregar nuevos alojamientos  

---

## 📞 ¿Necesitas Ayuda?

Si encuentras algún problema no listado aquí:

1. Revisa los logs de Apache en: `C:\xampp\apache\logs\error.log`
2. Revisa los logs de PHP en: `C:\xampp\php\logs\php_error_log`
3. Verifica la consola del navegador (F12) para errores de JavaScript

---

**¡Disfruta explorando AlojaTEC! 🏡✨**
