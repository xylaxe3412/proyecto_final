# Funcionalidad de Sincronización de Hábitos

## Descripción
Esta funcionalidad permite a los usuarios actualizar y sincronizar sus hábitos existentes con nuevas versiones de plantillas (templates) disponibles en el sistema. Esto asegura que los usuarios siempre tengan acceso al contenido más actualizado y mejorado.

## Características Implementadas

### 1. Sistema de Plantillas Versionadas
- **Modelo HabitTemplate**: Almacena plantillas con versiones, contenido, y metadatos
- **Versionado semántico**: Sistema de versiones (1.0, 1.1, 1.2, etc.)
- **Categorización**: Plantillas organizadas por categorías (salud, bienestar, productividad, aprendizaje)
- **Changelog**: Registro de cambios entre versiones

### 2. Vinculación Automática de Hábitos a Plantillas
- Los nuevos hábitos se vinculan automáticamente a plantillas según su categoría
- Mapeo automático:
  - `salud` → `ejercicio_diario`
  - `bienestar` → `meditacion_mindfulness`
  - `aprendizaje` → `lectura_diaria`
  - `productividad` → `organizacion_productividad`

### 3. API de Sincronización
Endpoints disponibles en `/habits/sync/`:

#### `GET /habits/sync/check-updates`
- Verifica qué hábitos tienen actualizaciones disponibles
- Retorna lista de hábitos con nuevas versiones

#### `POST /habits/sync/{habit}/sync`
- Sincroniza un hábito específico con su plantilla más reciente
- Parámetros:
  - `preserve_customizations` (boolean): Mantener configuraciones personalizadas

#### `POST /habits/sync/sync-all`
- Sincroniza todos los hábitos elegibles del usuario
- Actualización masiva con manejo de errores

#### `POST /habits/sync/{habit}/toggle-sync`
- Habilita/deshabilita la sincronización automática para un hábito

#### `GET /habits/sync/{habit}/history`
- Obtiene el historial de sincronización de un hábito

### 4. Interfaz de Usuario

#### Dashboard Principal
- **Botón "Actualizaciones"**: Acceso directo a la página de sincronización
- **Indicador visual**: Badge con número de actualizaciones disponibles
- **Verificación automática**: Chequeo de actualizaciones al cargar el dashboard

#### Página de Sincronización (`/habit-sync`)
- **Lista de actualizaciones**: Muestra hábitos con versiones nuevas disponibles
- **Información detallada**: Versión actual vs nueva, changelog, última sincronización
- **Opciones de sincronización**:
  - Actualizar preservando cambios personalizados
  - Restablecer completamente a la nueva versión
- **Gestión individual**: Sincronizar hábitos uno por uno
- **Sincronización masiva**: Actualizar todos los hábitos de una vez
- **Historial detallado**: Ver configuraciones y notas de sincronización

### 5. Campos de Base de Datos

#### Tabla `habits` (nuevos campos):
- `template_id`: ID de la plantilla asociada
- `template_version`: Versión actual de la plantilla
- `custom_settings`: Configuraciones personalizadas del usuario (JSON)
- `sync_enabled`: Si la sincronización está habilitada
- `last_synced_at`: Timestamp de última sincronización
- `sync_notes`: Notas sobre la última sincronización

#### Tabla `habit_templates`:
- `template_id`: Identificador único de la plantilla
- `name`, `description`, `categoria`: Información básica
- `version`: Versión de la plantilla
- `content`: Contenido estructurado (JSON)
- `quiz_questions`, `steps`, `tips`: Elementos específicos
- `changelog`: Descripción de cambios en la versión
- `is_active`: Si la plantilla está activa
- `difficulty_level`: Nivel de dificultad

## Flujo de Funcionamiento

### 1. Creación de Hábito
1. Usuario crea un nuevo hábito
2. Sistema asigna automáticamente `template_id` según categoría
3. Se establece la versión más reciente disponible
4. Se habilita `sync_enabled` por defecto

### 2. Detección de Actualizaciones
1. Dashboard verifica actualizaciones al cargar
2. Compara `template_version` del hábito con versión más reciente
3. Muestra indicador visual si hay actualizaciones

### 3. Proceso de Sincronización
1. Usuario accede a página de sincronización
2. Ve lista de hábitos con actualizaciones disponibles
3. Puede elegir sincronizar individualmente o en masa
4. Sistema actualiza contenido preservando o no las personalizaciones
5. Se registra la sincronización con timestamp y notas

## Plantillas Incluidas

### Plantillas v1.0:
1. **Ejercicio Diario** (`ejercicio_diario`)
2. **Meditación y Mindfulness** (`meditacion_mindfulness`)
3. **Lectura Diaria** (`lectura_diaria`)
4. **Organización y Productividad** (`organizacion_productividad`)

### Plantillas v1.1+ (para demostrar actualizaciones):
- **Ejercicio Diario v1.1**: Agregado seguimiento de progreso y rutinas personalizables
- **Meditación v1.2**: Nuevas técnicas de compasión y mindfulness en actividades diarias

## Datos de Prueba
Se incluye un seeder (`TestHabitsSeeder`) que crea:
- Usuario de prueba
- 4 hábitos de ejemplo vinculados a plantillas v1.0
- Datos para demostrar la funcionalidad de actualización

## Uso
1. Ejecutar migraciones: `php artisan migrate`
2. Poblar plantillas: `php artisan db:seed --class=HabitTemplateSeeder`
3. Crear datos de prueba: `php artisan db:seed --class=TestHabitsSeeder`
4. Acceder al dashboard y verificar el botón "Actualizaciones"
5. Probar la sincronización de hábitos

## Beneficios
- **Contenido actualizado**: Los usuarios siempre tienen acceso a las mejores prácticas
- **Flexibilidad**: Opción de preservar personalizaciones o adoptar completamente nuevas versiones
- **Transparencia**: Changelog detallado y historial de sincronización
- **Control del usuario**: Posibilidad de deshabilitar sincronización por hábito
- **Experiencia fluida**: Indicadores visuales y proceso intuitivo
