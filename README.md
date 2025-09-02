
# 🎯 Motiveo - Aplicación de Seguimiento de Hábitos

Una aplicación web minimalista y gamificada para ayudar a los usuarios a construir y mantener hábitos saludables de manera consistente.

## 📋 Tabla de Contenidos

- [Características Principales](#características-principales)
- [Tecnologías Utilizadas](#tecnologías-utilizadas)
- [Requisitos del Sistema](#requisitos-del-sistema)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Funcionalidades](#funcionalidades)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Base de Datos](#base-de-datos)
- [API Endpoints](#api-endpoints)
- [Comandos Artisan](#comandos-artisan)
- [Sistema de Gamificación](#sistema-de-gamificación)
- [Arquitectura del Frontend](#arquitectura-del-frontend)
- [Contribución](#contribución)
- [Licencia](#licencia)

## 🌟 Características Principales

### ✅ Seguimiento Diario Automatizado
- **Hábitos con duración específica**: 21, 30, 60 o 90 días
- **Progreso día a día**: Avance automático del contador diario
- **Validación temporal**: Solo se puede completar una vez por día
- **Fechas automáticas**: Sistema actualiza `next_due_date` automáticamente
- **Estado de racha**: Seguimiento de días consecutivos

### 🎮 Sistema de Gamificación
- **XP por acciones**:
  - Login diario: +10 XP
  - Crear hábito: +5 XP
  - Completar hábito: +20 XP
  - Completar quiz: +5 XP
- **Niveles progresivos**: Sistema de niveles basado en XP acumulado
- **Confetti animations**: Celebraciones visuales al subir de nivel
- **Prevención de trampa**: XP solo por acciones reales completadas

### 🧠 Quiz Interactivo Tipo Duolingo
- **Preguntas personalizadas** por categoría de hábito
- **Retroalimentación inmediata** con consejos motivacionales
- **Progreso visual** con barra de avance
- **Recompensas XP** por completar el quiz
- **Interfaz tipo Duolingo** con botones grandes y colores claros

### 💡 Sugerencias Inteligentes
- **Hábitos populares** basados en uso de otros usuarios
- **Categorización** por salud, productividad, bienestar y aprendizaje
- **Adopción rápida** con un clic
- **Filtrado automático** para evitar duplicados

### 🎨 Diseño Minimalista
- **Sin emojis**: Interfaz completamente profesional
- **Tipografía Inter**: Diseño moderno y legible
- **Colores sobrios**: Paleta de grises, azules y verdes
- **Responsive**: Optimizado para móvil y desktop
- **Animaciones suaves**: Transiciones elegantes

## 🛠 Tecnologías Utilizadas

### Backend
- **Laravel 11**: Framework PHP para desarrollo web
- **MySQL**: Base de datos relacional
- **Eloquent ORM**: Mapeo objeto-relacional
- **Middleware personalizado**: Para XP automático en login
- **Comandos Artisan**: Para tareas automatizadas

### Frontend
- **Alpine.js**: Framework JavaScript reactivo
- **Tailwind CSS**: Framework de utilidades CSS
- **Canvas Confetti**: Librería para animaciones de celebración
- **Blade Templates**: Motor de plantillas de Laravel

### Herramientas de Desarrollo
- **Composer**: Gestor de dependencias PHP
- **NPM**: Gestor de dependencias JavaScript
- **Vite**: Herramienta de build para assets
- **Git**: Control de versiones

## 📋 Requisitos del Sistema

- **PHP**: >= 8.2
- **Composer**: >= 2.0
- **Node.js**: >= 18.0
- **MySQL**: >= 8.0
- **Extensiones PHP**:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath

## 🚀 Instalación

### 1. Clonar el Repositorio
```bash
git clone https://github.com/xylaxe3412/proyecto_final.git
cd proyecto_final
```

### 2. Instalar Dependencias
```bash
# Dependencias PHP
composer install

# Dependencias JavaScript
npm install
```

### 3. Configurar Entorno
```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 4. Configurar Base de Datos
```bash
# Crear base de datos MySQL
mysql -u root -p
CREATE DATABASE motiveo_db;
exit

# Ejecutar migraciones
php artisan migrate

# Poblar con datos de prueba
php artisan db:seed --class=HabitSuggestionsSeeder
```

### 5. Compilar Assets
```bash
# Desarrollo
npm run dev

# Producción
npm run build
```

### 6. Iniciar Servidor
```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## ⚙️ Configuración

### Variables de Entorno (.env)

```env
APP_NAME=Motiveo
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=motiveo_db
DB_USERNAME=root
DB_PASSWORD=your-password

SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

### Configuración de Tareas Automatizadas

Para automatizar el reset diario de hábitos, agregar al crontab:

```bash
# Editar crontab
crontab -e

# Agregar línea (ejecutar a medianoche cada día)
0 0 * * * cd /path/to/project && php artisan habits:reset-daily
```

## 🎯 Funcionalidades

### 1. Sistema de Usuarios
- **Registro**: Formulario simple sin redes sociales
- **Login**: Autenticación con email/contraseña
- **Perfiles**: Información básica del usuario
- **XP automático**: +10 XP por login diario

### 2. Gestión de Hábitos

#### Crear Hábitos
- **Formulario guiado**: Proceso paso a paso
- **Categorías**: Salud, Productividad, Bienestar, Aprendizaje
- **Duración personalizable**: 21, 30, 60 o 90 días
- **Motivación**: Campo obligatorio para propósito personal
- **Recompensa**: Incentivo personal opcional

#### Seguimiento Diario
- **Visualización clara**: Día actual de total (ej: "Día 5 de 30")
- **Progreso visual**: Barra de progreso porcentual
- **Días restantes**: Contador descendente
- **Estado de racha**: Días consecutivos completados
- **Botón de completar**: Solo habilitado si no se completó hoy

#### Estados del Hábito
- **Activo**: Hábito en progreso, visible en dashboard
- **Completado hoy**: Marcado como hecho, no se puede repetir
- **Vencido**: Pasó el día sin completar, se reinicia racha
- **Finalizado**: Completó toda la duración establecida

### 3. Dashboard Principal

#### Módulo de Motivación
Mensajes dinámicos basados en el estado del usuario:
- "¡Excelente trabajo! Completaste tu hábito de [nombre] hoy"
- "¡Sigue así! Llevas X días seguidos con [hábito]"
- "Es hora de actuar. Completa tus hábitos de hoy"
- "Comienza tu viaje. Crea tu primer hábito"

#### Secciones del Dashboard
- **Hábitos Activos**: Lista de hábitos en progreso
- **Completados Hoy**: Hábitos ya realizados con hora
- **Estadísticas**: XP actual, nivel, progreso a siguiente nivel
- **Sugerencias**: Hábitos populares para adoptar

### 4. Quiz Interactivo

#### Características del Quiz
- **Duración**: 3-5 preguntas por sesión
- **Personalización**: Preguntas específicas por categoría de hábito
- **Progreso visual**: Barra de avance tipo Duolingo
- **Retroalimentación**: Mensaje inmediato por respuesta
- **Recompensa**: +5 XP al completar

#### Tipos de Preguntas por Categoría

**Salud**:
- Motivación para mantener el hábito
- Manejo de días sin completar
- Mejor momento para practicar
- Beneficios esperados

**Productividad**:
- Gestión de tiempo y prioridades
- Herramientas y técnicas
- Medición de resultados
- Eliminación de distracciones

**Bienestar**:
- Estado emocional y mental
- Técnicas de relajación
- Manejo del estrés
- Autocuidado

**Aprendizaje**:
- Métodos de estudio
- Recursos y materiales
- Aplicación práctica
- Evaluación del progreso

### 5. Sistema de Sugerencias

#### Categorías de Hábitos
- **Salud**: Ejercicio, nutrición, sueño, hidratación
- **Productividad**: Organización, enfoque, planificación
- **Bienestar**: Meditación, gratitud, conexión social
- **Aprendizaje**: Lectura, idiomas, habilidades técnicas

#### Algoritmo de Sugerencias
- **Popularidad**: Hábitos más adoptados por otros usuarios
- **Filtrado**: Excluye hábitos que el usuario ya tiene
- **Beneficios**: Descripción clara de ventajas
- **Adopción rápida**: Un clic para agregar a hábitos activos

## 📁 Estructura del Proyecto

```
proyecto_final/
├── app/
│   ├── Console/Commands/
│   │   └── ResetDailyHabits.php          # Comando para reset diario
│   ├── Http/Controllers/
│   │   ├── Auth/                          # Autenticación
│   │   ├── HabitController.php            # Gestión de hábitos
│   │   ├── QuizController.php             # Quiz interactivo
│   │   └── FormularioHabitoController.php # Formularios
│   ├── Http/Middleware/
│   │   └── LoginXpMiddleware.php          # XP automático en login
│   ├── Models/
│   │   ├── User.php                       # Modelo de usuario
│   │   ├── Habit.php                      # Modelo de hábito
│   │   ├── HabitSuggestion.php           # Sugerencias
│   │   └── HabitResponse.php             # Respuestas del quiz
│   └── Providers/
├── database/
│   ├── migrations/                        # Migraciones de BD
│   └── seeders/                          # Datos iniciales
├── resources/
│   ├── views/
│   │   ├── dashboard.blade.php           # Dashboard principal
│   │   ├── dashboard-new.blade.php       # Dashboard alternativo
│   │   ├── habit-quiz.blade.php          # Quiz interactivo
│   │   ├── auth/                         # Vistas de autenticación
│   │   └── layouts/                      # Plantillas base
│   ├── css/                              # Estilos CSS
│   └── js/                               # JavaScript
├── routes/
│   ├── web.php                           # Rutas web
│   └── auth.php                          # Rutas de autenticación
├── public/                               # Assets públicos
├── storage/                              # Archivos y logs
└── vendor/                               # Dependencias
```

## 🗄️ Base de Datos

### Tabla: users
```sql
- id (PK)
- name, email, password
- xp (integer) - Puntos de experiencia
- level (integer) - Nivel actual
- last_login_xp (timestamp) - Último login con XP
- created_at, updated_at
```

### Tabla: habits
```sql
- id (PK)
- user_id (FK)
- nombre - Nombre del hábito
- description - Descripción opcional
- categoria - salud|productividad|bienestar|aprendizaje
- frequency - diario|semanal
- motivation - Motivación personal
- reward - Recompensa opcional
- duration_days - Duración total (21, 30, 60, 90)
- current_day - Día actual del progreso
- start_date - Fecha de inicio
- next_due_date - Próxima fecha objetivo
- expected_end_date - Fecha estimada de finalización
- is_active - Si está activo
- completed_today - Si se completó hoy
- dias_racha - Días consecutivos
- last_completed_at - Última vez completado
- created_at, updated_at
```

### Tabla: habit_suggestions
```sql
- id (PK)
- name - Nombre del hábito
- description - Descripción
- categoria - Categoría
- popularity - Contador de adopciones
- benefits - Beneficios del hábito
- created_at, updated_at
```

### Tabla: habit_responses
```sql
- id (PK)
- habit_id (FK)
- user_name - Nombre del usuario
- current_state - Estado en el quiz
- responses (JSON) - Respuestas del cuestionario
- created_at, updated_at
```

## 🔌 API Endpoints

### Autenticación
```
POST /register - Registro de usuario
POST /login - Inicio de sesión
POST /logout - Cerrar sesión
```

### Hábitos
```
GET /api/user-habits - Obtener hábitos del usuario
POST /habits - Crear hábito personalizado
POST /habits/{id}/complete - Completar hábito
POST /habits/create-from-suggestion - Adoptar sugerencia
```

### Sugerencias
```
GET /api/suggestions - Obtener sugerencias por categoría
```

### Quiz
```
GET /quiz - Mostrar página del quiz
POST /quiz/complete - Completar quiz y otorgar XP
```

### Dashboard
```
GET / - Dashboard principal
GET /dashboard-new - Dashboard alternativo
```

## ⚡ Comandos Artisan

### Comandos Principales
```bash
# Migrar base de datos
php artisan migrate

# Poblar con datos iniciales
php artisan db:seed

# Reset diario de hábitos
php artisan habits:reset-daily

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Generar clave de aplicación
php artisan key:generate
```

### Comandos de Desarrollo
```bash
# Crear migración
php artisan make:migration nombre_migracion

# Crear modelo
php artisan make:model NombreModelo

# Crear controlador
php artisan make:controller NombreController

# Crear comando personalizado
php artisan make:command NombreComando
```

## 🎮 Sistema de Gamificación

### Mecánicas de XP

#### Fuentes de Experiencia
- **Login Diario**: +10 XP (una vez por día)
- **Crear Hábito**: +5 XP (personal o desde sugerencia)
- **Completar Hábito**: +20 XP (por cada completado)
- **Completar Quiz**: +5 XP (por sesión completa)

#### Sistema de Niveles
```php
// Fórmula: nivel = floor(sqrt(xp / 100)) + 1
Nivel 1: 0-99 XP
Nivel 2: 100-399 XP
Nivel 3: 400-899 XP
Nivel 4: 900-1599 XP
Nivel 5: 1600-2499 XP
// Continúa con raíz cuadrada
```

#### Prevención de Trampa
- **Un login XP por día**: Middleware verifica última fecha
- **Completar una vez**: Validación en base de datos
- **Fecha correcta**: Solo se puede completar en fecha debida
- **Hábito activo**: Solo hábitos activos otorgan XP

### Animaciones de Celebración

#### Confetti al Subir de Nivel
```javascript
// Animación en 3 fases
1. Confetti central (100 partículas)
2. Confetti izquierdo (50 partículas, 200ms delay)
3. Confetti derecho (50 partículas, 400ms delay)

// Colores del tema
['#3b82f6', '#10b981', '#f59e0b']
```

#### Integración Frontend
- Detección automática de level-up en servidor
- Respuesta JSON incluye `leveled_up: true`
- Frontend dispara confetti automáticamente
- Notificación con nuevo nivel

## 🎨 Arquitectura del Frontend

### Alpine.js - Reactividad

#### Componente Principal (habitApp)
```javascript
{
  // Estado
  activeHabits: [],
  completedHabits: [],
  userStats: {},
  suggestions: {},
  
  // Modales
  showCreateModal: false,
  
  // Funciones principales
  loadUserHabits(),
  completeHabit(),
  submitCreateForm(),
  adoptSuggestion(),
  launchConfetti()
}
```

#### Gestión de Estado
- **Reactivo**: Cambios automáticos en UI
- **API calls**: Fetch para comunicación con servidor
- **Notificaciones**: Sistema de mensajes temporales
- **Persistencia**: Recarga automática tras acciones

### Tailwind CSS - Styling

#### Configuración Personalizada
```javascript
theme: {
  extend: {
    colors: {
      'primary': '#1f2937',
      'secondary': '#374151', 
      'accent': '#3b82f6',
      'success': '#10b981',
      'warning': '#f59e0b',
      'danger': '#ef4444'
    },
    fontFamily: {
      'sans': ['Inter', 'system-ui', 'sans-serif']
    }
  }
}
```

#### Componentes Reutilizables
- **Botones**: Clases consistentes para acciones
- **Cards**: Contenedores con backdrop blur
- **Modales**: Overlay con animaciones
- **Progreso**: Barras animadas con gradientes
- **Notificaciones**: Toast messages con transiciones

### Canvas Confetti - Animaciones

#### Configuración de Celebración
```javascript
// Explosión principal
confetti({
  particleCount: 100,
  spread: 70,
  origin: { y: 0.6 },
  colors: ['#3b82f6', '#10b981', '#f59e0b']
});

// Efectos laterales con delay
setTimeout(() => {
  confetti({
    particleCount: 50,
    angle: 60,
    spread: 55,
    origin: { x: 0 }
  });
}, 200);
```

## 🧪 Testing

### Tests de Funcionalidad
```bash
# Ejecutar todos los tests
php artisan test

# Tests específicos
php artisan test --filter HabitTest
php artisan test --filter UserTest
php artisan test --filter QuizTest
```

### Casos de Prueba Principales

#### Sistema de Hábitos
- ✅ Crear hábito con duración específica
- ✅ Completar hábito solo una vez por día
- ✅ Avance automático de día actual
- ✅ Finalización automática al completar duración
- ✅ Reset de racha por días saltados

#### Sistema de XP
- ✅ Otorgar XP por login diario (solo una vez)
- ✅ Otorgar XP por crear hábito
- ✅ Otorgar XP por completar hábito
- ✅ Detectar subida de nivel correctamente
- ✅ Prevenir XP duplicado

#### Quiz Interactivo
- ✅ Cargar preguntas por categoría
- ✅ Retroalimentación por respuesta
- ✅ Progreso visual correcto
- ✅ Otorgar XP al finalizar
- ✅ Integración con confetti

## 🚀 Deployment

### Preparación para Producción

#### 1. Optimización
```bash
# Optimizar Composer
composer install --optimize-autoloader --no-dev

# Compilar assets para producción
npm run build

# Cachear configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 2. Variables de Entorno
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

# Base de datos de producción
DB_CONNECTION=mysql
DB_HOST=tu-servidor-bd
DB_DATABASE=motiveo_production
DB_USERNAME=usuario_produccion
DB_PASSWORD=contraseña_segura
```

#### 3. Seguridad
```bash
# Generar nueva clave de aplicación
php artisan key:generate

# Configurar permisos
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Servidor Web (Apache/Nginx)

#### Configuración Apache
```apache
<VirtualHost *:80>
    DocumentRoot /path/to/proyecto_final/public
    ServerName tu-dominio.com
    
    <Directory /path/to/proyecto_final/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Configuración Nginx
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /path/to/proyecto_final/public;
    
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}
```

## 🤝 Contribución

### Proceso de Desarrollo

#### 1. Fork y Clone
```bash
# Fork el repositorio en GitHub
# Clonar tu fork
git clone https://github.com/tu-usuario/proyecto_final.git
cd proyecto_final
```

#### 2. Crear Rama de Feature
```bash
# Crear nueva rama
git checkout -b feature/nueva-funcionalidad

# Hacer cambios y commits
git add .
git commit -m "Añadir nueva funcionalidad"

# Push a tu fork
git push origin feature/nueva-funcionalidad
```

#### 3. Pull Request
- Crear PR desde tu fork al repositorio principal
- Describir cambios claramente
- Incluir tests si es necesario
- Esperar revisión del código

### Estándares de Código

#### PHP (PSR-12)
```php
<?php

namespace App\Http\Controllers;

class EjemploController extends Controller
{
    public function metodoEjemplo(): JsonResponse
    {
        // Lógica clara y comentada
        return response()->json(['success' => true]);
    }
}
```

#### JavaScript (ESLint)
```javascript
// Usar const/let en lugar de var
const habitApp = () => {
    return {
        // Propiedades claras
        activeHabits: [],
        
        // Métodos documentados
        async loadHabits() {
            // Implementación
        }
    };
};
```

### Convenciones de Nombres

#### Base de Datos
- **Tablas**: snake_case plural (`habits`, `user_habits`)
- **Columnas**: snake_case (`created_at`, `next_due_date`)
- **Índices**: descriptivos (`idx_habits_user_active`)

#### Código
- **Clases**: PascalCase (`HabitController`)
- **Métodos**: camelCase (`markCompleted`)
- **Variables**: camelCase (`activeHabits`)
- **Constantes**: UPPER_SNAKE_CASE (`MAX_HABIT_DURATION`)

## 📚 Recursos Adicionales

### Documentación Oficial
- [Laravel 11](https://laravel.com/docs/11.x)
- [Alpine.js](https://alpinejs.dev/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Canvas Confetti](https://github.com/catdad/canvas-confetti)

### Tutoriales Relacionados
- [Laravel Eloquent ORM](https://laravel.com/docs/11.x/eloquent)
- [Alpine.js Reactivity](https://alpinejs.dev/essentials/reactivity)
- [Tailwind Responsive Design](https://tailwindcss.com/docs/responsive-design)

### Herramientas de Desarrollo
- [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)
- [Laravel Telescope](https://laravel.com/docs/11.x/telescope)
- [PHPStorm Laravel Plugin](https://plugins.jetbrains.com/plugin/7532-laravel)

## 🐛 Solución de Problemas

### Errores Comunes

#### 1. Error de Clave de Aplicación
```bash
# Síntoma: "No application encryption key has been specified"
# Solución:
php artisan key:generate
```

#### 2. Error de Permisos
```bash
# Síntoma: No se pueden escribir archivos
# Solución:
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
```

#### 3. Error de Migración
```bash
# Síntoma: Base de datos no encontrada
# Solución:
# 1. Verificar configuración en .env
# 2. Crear base de datos manualmente
# 3. Ejecutar migraciones
php artisan migrate:fresh --seed
```

#### 4. Assets No Cargan
```bash
# Síntoma: CSS/JS no se cargan
# Solución:
npm run build
php artisan view:clear
```

### Debug de XP
```php
// En HabitController o User model
\Log::info("XP Debug", [
    'user_id' => $user->id,
    'current_xp' => $user->xp,
    'action' => 'complete_habit',
    'xp_gained' => 20
]);
```

### Debug de Confetti
```javascript
// En el navegador (DevTools Console)
console.log('Confetti triggered:', {
    leveled_up: data.leveled_up,
    new_level: data.new_level,
    user_stats: data.user_stats
});
```

## 📞 Soporte

### Contacto
- **Desarrollador**: Xylaxe3412
- **Email**: [correo@ejemplo.com]
- **GitHub**: [@xylaxe3412](https://github.com/xylaxe3412)

### Reportar Bugs
1. Ir a [Issues](https://github.com/xylaxe3412/proyecto_final/issues)
2. Crear nuevo issue
3. Usar template de bug report
4. Incluir información de sistema y pasos para reproducir

### Solicitar Features
1. Ir a [Issues](https://github.com/xylaxe3412/proyecto_final/issues)
2. Crear nuevo issue con label "enhancement"
3. Describir funcionalidad deseada
4. Explicar caso de uso

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

### MIT License
```
Copyright (c) 2025 Xylaxe3412

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 🎉 Agradecimientos

- **Laravel Team** por el excelente framework
- **Alpine.js Community** por la reactividad simple
- **Tailwind Labs** por las utilidades CSS
- **Catdad** por la librería Canvas Confetti
- **Comunidad Open Source** por la inspiración y recursos

## 🔧 Instalación Rápida

### Método 1: Script Automático (Recomendado)

**Windows:**
```powershell
# Ejecutar PowerShell como administrador
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
.\setup.ps1
```

**Linux/macOS:**
```bash
chmod +x setup.sh
./setup.sh
```

### Método 2: Instalación Manual

Consulta [INSTALLATION.md](INSTALLATION.md) para instrucciones detalladas paso a paso.

## 🆘 Solución de Problemas Comunes

### Error al clonar desde GitHub en otro PC

Si experimentas errores con los modelos o la base de datos al descargar el proyecto:

1. **Configurar el entorno correctamente:**
```bash
# Copiar configuración de ejemplo
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

2. **Problema con base de datos:**
```bash
# Para SQLite (más fácil)
touch database/database.sqlite
php artisan migrate --seed

# Para MySQL
mysql -u root -p
CREATE DATABASE proyecto_final;
exit
php artisan migrate --seed
```

3. **Error "Clase no encontrada":**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

4. **Verificar la instalación:**
   - Visita: `http://localhost:8000/debug/habits` (después de login)
   - Esta ruta proporciona información detallada sobre posibles problemas

### Errores Comunes y Soluciones

| Error | Solución |
|-------|----------|
| `Base64 decoder invalid` | Ejecutar `php artisan key:generate` |
| `Database not found` | Verificar configuración en `.env` |
| `Class not found` | Ejecutar `composer dump-autoload` |
| `Permission denied` | Configurar permisos: `chmod -R 775 storage bootstrap/cache` |
| `Assets not found` | Ejecutar `npm install && npm run build` |

---

**¡Comienza tu viaje hacia mejores hábitos hoy! 🚀**

*Motiveo - Donde cada día cuenta hacia tus objetivos*

Este proyecto está licenciado bajo la [MIT License](https://opensource.org/licenses/MIT).
