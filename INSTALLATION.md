# 🚀 Guía de Instalación - Proyecto Final

## 📋 Requisitos Previos

### Software Necesario:
- **PHP 8.2+** con extensiones: `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `ctype`, `json`
- **Composer** (gestor de dependencias PHP)
- **Node.js 18+** y **npm**
- **Git**

### Base de Datos (una de las siguientes):
- **MySQL 8.0+** (recomendado)
- **SQLite** (más fácil para desarrollo)

## 🔧 Instalación Paso a Paso

### 1. Clonar el Repositorio
```bash
git clone https://github.com/xylaxe3412/proyecto_final.git
cd proyecto_final
```

### 2. Instalar Dependencias PHP
```bash
composer install
```

### 3. Instalar Dependencias Node.js
```bash
npm install
```

### 4. Configuración del Entorno

#### Opción A: MySQL (Recomendado)
```bash
# Copiar archivo de configuración
cp .env.example .env

# Editar .env con tus configuraciones:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=proyecto_final
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

#### Opción B: SQLite (Más Fácil)
```bash
# Copiar archivo de configuración
cp .env.example .env

# Editar .env para usar SQLite:
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
# Comentar o eliminar otras líneas DB_*
```

### 5. Generar Clave de Aplicación
```bash
php artisan key:generate
```

### 6. Configurar Base de Datos

#### Para SQLite:
```bash
# Crear archivo de base de datos
touch database/database.sqlite
```

#### Para MySQL:
```bash
# Crear base de datos en MySQL
mysql -u root -p
CREATE DATABASE proyecto_final;
exit
```

### 7. Ejecutar Migraciones y Seeders
```bash
php artisan migrate --seed
```

### 8. Compilar Assets Frontend
```bash
npm run build
```

### 9. Configurar Permisos (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

## 🔑 Configuraciones Opcionales

### Google OAuth (Opcional)
Si quieres usar login con Google, agrega en `.env`:
```
GOOGLE_CLIENT_ID=tu_client_id
GOOGLE_CLIENT_SECRET=tu_client_secret
```

### Firebase (Opcional)
Para funciones avanzadas con Firebase:
```
FIREBASE_CREDENTIALS=path/to/firebase-credentials.json
```

## 🚀 Ejecutar el Proyecto

### Desarrollo:
```bash
# Terminal 1: Servidor PHP
php artisan serve

# Terminal 2: Build de assets en tiempo real
npm run dev
```

### Producción:
```bash
# Compilar assets para producción
npm run build

# Configurar servidor web (Apache/Nginx)
```

## 🔍 Verificar Instalación

Visita: `http://localhost:8000`

Para verificar que todo funciona correctamente:
- `http://localhost:8000/debug-habits` (requiere login)

## ❗ Solución de Problemas

### Error: "Base de datos no encontrada"
- Verifica que la base de datos existe
- Revisa las credenciales en `.env`
- Para SQLite: verifica que el archivo existe

### Error: "Clase no encontrada"
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Error: "Clave de aplicación no configurada"
```bash
php artisan key:generate
```

### Error: "Permisos denegados"
```bash
# Linux/Mac
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## 📁 Estructura del Proyecto

```
proyecto_final/
├── app/                    # Lógica de la aplicación
├── database/              # Migraciones y seeders
├── public/                # Archivos públicos
├── resources/             # Vistas y assets
├── routes/                # Definición de rutas
├── storage/               # Archivos temporales
├── .env                   # Configuración del entorno
└── composer.json          # Dependencias PHP
```

## 🆘 Soporte

Si encuentras problemas, revisa:
1. Los logs en `storage/logs/laravel.log`
2. Que todos los requisitos estén instalados
3. Que el archivo `.env` esté configurado correctamente

## 🎯 Funcionalidades del Proyecto

- ✅ Sistema de autenticación con email/password
- ✅ Login con Google OAuth
- ✅ Gestión de hábitos personalizados
- ✅ Animaciones Lottie para hábitos de ejercicio
- ✅ Sistema de puntos y niveles
- ✅ Dashboard interactivo
- ✅ Sugerencias de hábitos populares
