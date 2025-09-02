#!/bin/bash

# 🚀 Script de Instalación Automática - Proyecto Final
# Este script configura automáticamente el proyecto en una nueva máquina

echo "🚀 Iniciando configuración del proyecto..."

# Colores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Función para imprimir mensajes de estado
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Verificar si PHP está instalado
if ! command -v php &> /dev/null; then
    print_error "PHP no está instalado. Por favor instala PHP 8.2 o superior."
    exit 1
fi

# Verificar si Composer está instalado
if ! command -v composer &> /dev/null; then
    print_error "Composer no está instalado. Por favor instala Composer."
    exit 1
fi

# Verificar si Node.js está instalado
if ! command -v node &> /dev/null; then
    print_error "Node.js no está instalado. Por favor instala Node.js 18 o superior."
    exit 1
fi

print_status "Verificaciones iniciales completadas"

# Instalar dependencias de Composer
print_status "Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader

if [ $? -ne 0 ]; then
    print_error "Error al instalar dependencias PHP"
    exit 1
fi

# Instalar dependencias de npm
print_status "Instalando dependencias Node.js..."
npm install

if [ $? -ne 0 ]; then
    print_error "Error al instalar dependencias Node.js"
    exit 1
fi

# Configurar archivo .env
if [ ! -f .env ]; then
    print_status "Creando archivo .env..."
    cp .env.example .env
    
    # Generar clave de aplicación
    php artisan key:generate
    
    print_warning "Por favor configura tu base de datos en el archivo .env"
    print_warning "Opciones:"
    print_warning "1. SQLite (más fácil): ya está configurado"
    print_warning "2. MySQL: edita las líneas DB_* en .env"
else
    print_warning "El archivo .env ya existe, no se sobrescribió"
fi

# Crear base de datos SQLite si no existe
if [ ! -f database/database.sqlite ]; then
    print_status "Creando base de datos SQLite..."
    touch database/database.sqlite
fi

# Ejecutar migraciones
print_status "Ejecutando migraciones de base de datos..."
php artisan migrate --force

if [ $? -ne 0 ]; then
    print_error "Error al ejecutar migraciones. Verifica tu configuración de base de datos."
    exit 1
fi

# Ejecutar seeders
print_status "Poblando base de datos con datos iniciales..."
php artisan db:seed --force

# Limpiar caché
print_status "Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Compilar assets
print_status "Compilando assets frontend..."
npm run build

# Configurar permisos (solo en Unix)
if [[ "$OSTYPE" == "linux-gnu"* ]] || [[ "$OSTYPE" == "darwin"* ]]; then
    print_status "Configurando permisos..."
    chmod -R 775 storage bootstrap/cache
fi

print_status "🎉 ¡Instalación completada exitosamente!"
echo ""
echo "Para ejecutar el proyecto:"
echo "1. Servidor de desarrollo: php artisan serve"
echo "2. Compilación en tiempo real: npm run dev"
echo ""
echo "URLs importantes:"
echo "- Aplicación: http://localhost:8000"
echo "- Debug de hábitos: http://localhost:8000/debug/habits (requiere login)"
echo ""
print_warning "Si usas MySQL, asegúrate de configurar las credenciales en .env"
