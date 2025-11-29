#!/bin/bash

echo "🚀 Inicializando InventCraft en Docker..."

# Esperar a que MySQL esté listo
echo "⏳ Esperando a que MySQL esté listo..."
until docker exec inventcraft_db mysqladmin ping -h localhost --silent; do
    sleep 1
done

echo "✅ MySQL está listo"

# Instalar dependencias de Composer
echo "📦 Instalando dependencias de Composer..."
docker exec inventcraft_app composer install --no-interaction

# Instalar dependencias de NPM
echo "📦 Instalando dependencias de NPM..."
docker exec inventcraft_app npm install

# Compilar assets
echo "🎨 Compilando assets..."
docker exec inventcraft_app npm run build

# Copiar .env si no existe
if [ ! -f .env ]; then
    echo "📝 Creando archivo .env..."
    cp .env.example .env
    docker exec inventcraft_app php artisan key:generate
fi

# Ejecutar migraciones
echo "🗄️ Ejecutando migraciones..."
docker exec inventcraft_app php artisan migrate --force

# Ejecutar seeders
echo "🌱 Ejecutando seeders..."
docker exec inventcraft_app php artisan db:seed --force

# Limpiar cache
echo "🧹 Limpiando cache..."
docker exec inventcraft_app php artisan config:clear
docker exec inventcraft_app php artisan cache:clear
docker exec inventcraft_app php artisan view:clear
docker exec inventcraft_app php artisan route:clear

echo "✅ ¡InventCraft está listo!"
echo "🌐 Accede a: http://localhost:8080"

