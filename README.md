# Proyecto Final - Sistema de Gestión de Hábitos

## Descripción

Sistema web de gestión de hábitos desarrollado con Laravel que permite a los usuarios crear, seguir y completar hábitos personalizados con un sistema de gamificación basado en XP y niveles.

## Características Principales

- **Dashboard interactivo** - Vista en grid de hábitos con diseño responsivo
- **Sistema de gamificación** - XP y niveles por completar hábitos
- **Modal expandido** - Vista detallada de hábitos con guías paso a paso
- **Diferenciación visual** - Estados claros entre hábitos completados y pendientes
- **Funcionalidad de deshacer** - Revertir hábitos completados accidentalmente
- **Sugerencias de hábitos** - Catálogo de hábitos populares organizados por categoría
- **Autenticación** - Sistema de login y registro de usuarios
- **Tema dark** - Diseño moderno con colores motiveo

## Tecnologías Utilizadas

- **Backend**: Laravel (PHP)
- **Frontend**: Blade Templates, Alpine.js, Tailwind CSS
- **Base de datos**: MySQL/SQLite
- **Autenticación**: Laravel Breeze
- **Iconos**: FontAwesome
- **Animaciones**: Canvas Confetti

## Instalación

1. Clonar el repositorio
```bash
git clone https://github.com/xylaxe3412/proyecto_final.git
cd proyecto_final
```

2. Instalar dependencias
```bash
composer install
npm install
```

3. Configurar el archivo .env
```bash
cp .env.example .env
php artisan key:generate
```

4. Ejecutar migraciones y seeders
```bash
php artisan migrate --seed
```

5. Compilar assets
```bash
npm run dev
```

6. Iniciar servidor
```bash
php artisan serve
```

## Estructura del Proyecto

- `app/Models/` - Modelos de datos (User, Habit, HabitSuggestion)
- `app/Http/Controllers/` - Controladores de la aplicación
- `resources/views/` - Vistas Blade
- `database/migrations/` - Migraciones de base de datos
- `database/seeders/` - Seeders con datos de ejemplo

## Licencia

Este proyecto está licenciado bajo la [MIT License](https://opensource.org/licenses/MIT).
