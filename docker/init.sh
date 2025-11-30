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

# Si existe composer.lock, instalar desde lock. Si no, actualizar y generar lock.
if [ -f composer.lock ]; then
    docker exec inventcraft_app composer install --no-interaction
else
    echo "⚠️  No se encontró composer.lock, generando uno nuevo..."
    docker exec inventcraft_app composer update --no-interaction
fi

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
fi

# Configurar variables de base de datos en .env (asegurar que estén correctas para Docker)
echo "🔧 Configurando variables de base de datos..."
sed -i.bak 's/^DB_HOST=.*/DB_HOST=db/' .env
sed -i.bak 's/^DB_PORT=.*/DB_PORT=3306/' .env
sed -i.bak 's/^DB_DATABASE=.*/DB_DATABASE=inventcraft/' .env
sed -i.bak 's/^DB_USERNAME=.*/DB_USERNAME=inventcraft/' .env
sed -i.bak 's/^DB_PASSWORD=.*/DB_PASSWORD=inventcraft/' .env
rm -f .env.bak

# Generar APP_KEY si no existe
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "🔑 Generando clave de aplicación..."
    docker exec inventcraft_app php artisan key:generate
fi

# Limpiar caché de configuración antes de conectar a la BD
echo "🧹 Limpiando caché de configuración..."
docker exec inventcraft_app php artisan config:clear

# Esperar un poco más para asegurar que MySQL está completamente listo
echo "⏳ Esperando a que MySQL esté completamente listo..."
sleep 3

# Ejecutar migraciones y seeders
echo "🗄️ Ejecutando migraciones..."
docker exec inventcraft_app php artisan migrate:fresh --seed --force

# Limpiar cache
echo "🧹 Limpiando cache..."
docker exec inventcraft_app php artisan config:clear
docker exec inventcraft_app php artisan cache:clear
docker exec inventcraft_app php artisan view:clear
docker exec inventcraft_app php artisan route:clear

echo "✅ ¡InventCraft está listo!"
echo "🌐 Accede a: http://localhost:8080"

