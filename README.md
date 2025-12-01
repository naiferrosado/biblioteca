# 📚 Sistema de Gestión de Biblioteca (Proyecto Final de Web II)

![Estado del Proyecto](https://img.shields.io/badge/Estado-Terminado-success)
![Tecnología](https://img.shields.io/badge/PHP-8.2-blue)
![DB](https://img.shields.io/badge/MySQL-PDO-orange)
![Frontend](https://img.shields.io/badge/Bootstrap-5-purple)

Sistema Web integral para la administración de préstamos de libros, control de inventario y gestión de usuarios. Desarrollado con una arquitectura modular basada en **PHP Nativo** y **MySQL**, implementando patrones de seguridad y diseño responsivo.

## 🚀 Características Principales

### 🔐 Módulo de Usuarios y Seguridad
- [cite_start]**Roles Diferenciados:** Administrador (Control total) y Lector (Solo lectura/solicitud)[cite: 25].
- **Autenticación Segura:** Login y Registro con contraseñas encriptadas (`password_hash`).
- **Gestión de Perfil:** Los usuarios pueden actualizar sus datos, cambiar contraseña y subir **Foto de Perfil**.
- **Administración:** El admin puede buscar, filtrar y suspender usuarios (Ban/Unban).

### 📖 Módulo de Libros (Inventario)
- **CRUD Completo:** Crear, Leer, Editar y Eliminar libros .
- **Gestión Multimedia:** Subida de imágenes de portada.
- [cite_start]**Borrado Lógico:** Los libros no se eliminan de la BD, solo se desactivan para proteger el historial[cite: 31].
- **Control de Stock:** Visualización de disponibilidad en tiempo real.

### 🔄 Módulo de Préstamos (Workflow)
- **Flujo de Aprobación:** 1. Usuario solicita libro (Stock reservado).
  2. Admin aprueba o rechaza la solicitud.
  3. Admin recibe la devolución (Stock restaurado).
- **Transacciones SQL:** Uso de `beginTransaction` y `commit` para asegurar la integridad del inventario durante los préstamos.
- **Historial:** Vista diferenciada para Admin (ve todo) y Usuario (ve solo sus préstamos).

---

## 🛠️ Requisitos del Sistema

- **Servidor Web:** Apache (XAMPP, WAMP, MAMP).
- **PHP:** Versión 8.0 o superior.
- **Base de Datos:** MySQL / MariaDB.
- **Navegador:** Google Chrome, Firefox, Brave, Edge.

---

## 📦 Instalación y Configuración

1. **Clonar/Descargar:**
   Descomprime el proyecto en la carpeta `htdocs` de tu servidor local.
   > Ruta recomendada: `C:/xampp/htdocs/biblioteca-proyecto`

2. **Base de Datos:**
   - Abre **phpMyAdmin**.
   - Crea una base de datos llamada `biblioteca_db`.
   - Importa el script SQL proporcionado abajo o el archivo `database.sql` si existe.

3. **Configuración:**
   Verifica el archivo `config/db.php`. Si tienes contraseña en tu MySQL, edita la variable `$pass`.

   ```php
   $host = 'localhost';
   $db   = 'biblioteca_db';
   $user = 'root';
   $pass = ''; // Tu contraseña aquí
