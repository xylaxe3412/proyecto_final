# 🚀 Script de Instalación Automática - Proyecto Final (Windows)
# Este script configura automáticamente el proyecto en una nueva máquina Windows

Write-Host "🚀 Iniciando configuración del proyecto..." -ForegroundColor Green

function Write-Success {
    param($Message)
    Write-Host "✅ $Message" -ForegroundColor Green
}

function Write-Warning {
    param($Message)
    Write-Host "⚠️  $Message" -ForegroundColor Yellow
}

function Write-Error {
    param($Message)
    Write-Host "❌ $Message" -ForegroundColor Red
}

# Verificar si PHP está instalado
try {
    $phpVersion = php -v 2>$null
    if ($LASTEXITCODE -ne 0) { throw }
    Write-Success "PHP encontrado"
} catch {
    Write-Error "PHP no está instalado. Por favor instala PHP 8.2 o superior."
    Read-Host "Presiona Enter para salir"
    exit 1
}

# Verificar si Composer está instalado
try {
    $composerVersion = composer --version 2>$null
    if ($LASTEXITCODE -ne 0) { throw }
    Write-Success "Composer encontrado"
} catch {
    Write-Error "Composer no está instalado. Por favor instala Composer."
    Read-Host "Presiona Enter para salir"
    exit 1
}

# Verificar si Node.js está instalado
try {
    $nodeVersion = node --version 2>$null
    if ($LASTEXITCODE -ne 0) { throw }
    Write-Success "Node.js encontrado"
} catch {
    Write-Error "Node.js no está instalado. Por favor instala Node.js 18 o superior."
    Read-Host "Presiona Enter para salir"
    exit 1
}

Write-Success "Verificaciones iniciales completadas"

# Instalar dependencias de Composer
Write-Success "Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader

if ($LASTEXITCODE -ne 0) {
    Write-Error "Error al instalar dependencias PHP"
    Read-Host "Presiona Enter para salir"
    exit 1
}

# Instalar dependencias de npm
Write-Success "Instalando dependencias Node.js..."
npm install

if ($LASTEXITCODE -ne 0) {
    Write-Error "Error al instalar dependencias Node.js"
    Read-Host "Presiona Enter para salir"
    exit 1
}

# Configurar archivo .env
if (-not (Test-Path .env)) {
    Write-Success "Creando archivo .env..."
    Copy-Item .env.example .env
    
    # Generar clave de aplicación
    php artisan key:generate
    
    Write-Warning "Por favor configura tu base de datos en el archivo .env"
    Write-Warning "Opciones:"
    Write-Warning "1. SQLite (más fácil): ya está configurado"
    Write-Warning "2. MySQL: edita las líneas DB_* en .env"
} else {
    Write-Warning "El archivo .env ya existe, no se sobrescribió"
}

# Crear base de datos SQLite si no existe
if (-not (Test-Path database/database.sqlite)) {
    Write-Success "Creando base de datos SQLite..."
    New-Item database/database.sqlite -ItemType File
}

# Ejecutar migraciones
Write-Success "Ejecutando migraciones de base de datos..."
php artisan migrate --force

if ($LASTEXITCODE -ne 0) {
    Write-Error "Error al ejecutar migraciones. Verifica tu configuración de base de datos."
    Read-Host "Presiona Enter para salir"
    exit 1
}

# Ejecutar seeders
Write-Success "Poblando base de datos con datos iniciales..."
php artisan db:seed --force

# Limpiar caché
Write-Success "Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Compilar assets
Write-Success "Compilando assets frontend..."
npm run build

Write-Success "🎉 ¡Instalación completada exitosamente!"
Write-Host ""
Write-Host "Para ejecutar el proyecto:"
Write-Host "1. Servidor de desarrollo: php artisan serve"
Write-Host "2. Compilación en tiempo real: npm run dev"
Write-Host ""
Write-Host "URLs importantes:"
Write-Host "- Aplicación: http://localhost:8000"
Write-Host "- Debug de hábitos: http://localhost:8000/debug/habits (requiere login)"
Write-Host ""
Write-Warning "Si usas MySQL, asegúrate de configurar las credenciales en .env"

Read-Host "Presiona Enter para continuar"
