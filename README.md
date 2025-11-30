# 🚀 Guía de Despliegue de InventCraft

Esta guía te ayudará a desplegar InventCraft en tu entorno local o servidor.

> **¿Buscas las instrucciones del juego?** Consulta el archivo [README_JUEGO.md](README_JUEGO.md) para conocer las reglas, mecánicas y objetivos del juego.

## Requisitos del Sistema

### Opción 1: Con Docker (Recomendado)

- **Docker Desktop**: Última versión
- **Docker Compose**: Incluido en Docker Desktop

### Opción 2: Instalación Local (sin Docker)

- **PHP**: 8.1 o superior
- **Composer**: Última versión
- **Node.js**: 16.x o superior
- **NPM**: 8.x o superior
- **MySQL**: 8.0 o superior
- **Extensiones PHP requeridas**:
  - `pdo_mysql`
  - `mbstring`
  - `exif`
  - `pcntl`
  - `bcmath`
  - `gd`
  - `zip`

---

## Despliegue con Docker (Recomendado)

Esta es la forma más sencilla de desplegar el proyecto.

### 1. Clonar el repositorio

```bash
git clone https://github.com/CynthiaRubio/Proyecto_InventCraft.git
cd Proyecto_InventCraft
```

### 2. Construir y levantar los contenedores

```bash
docker compose up -d --build
```

Este comando creará y levantará tres contenedores:
- **app**: Servidor PHP-FPM 8.3
- **nginx**: Servidor web Nginx
- **db**: Base de datos MySQL 8.0

### 3. Inicializar la aplicación

Ejecuta el script de inicialización:

```bash
./docker/init.sh
```

Este script automáticamente:
- Instala las dependencias de Composer (maneja automáticamente problemas con dependencias obsoletas)
- Instala las dependencias de NPM
- Compila los assets (CSS/JS)
- Crea el archivo `.env` desde `.env.example` si no existe
- Configura las variables de base de datos para Docker
- Genera la clave de la aplicación
- Ejecuta las migraciones y seeders para poblar la base de datos
- Limpia las cachés

### 4. Configurar variables de entorno (Opcional)

El script de inicialización configura automáticamente:
- Crea el archivo `.env` desde `.env.example` si no existe
- Configura las variables de base de datos para Docker (`DB_HOST=db`, `DB_PORT=3306`, etc.)
- Genera la clave de aplicación (`APP_KEY`)

Si necesitas configurar variables adicionales, edita el archivo `.env` después de ejecutar el script:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=inventcraft
DB_USERNAME=inventcraft
DB_PASSWORD=inventcraft

# Opcional: API Key de Freesound (para sonidos de zonas)
FREESOUND_API_KEY=tu_api_key_aqui
```

### 5. Acceder a la aplicación

- **Aplicación web**: http://localhost:8080
- **Base de datos**: localhost:3306
  - Usuario: `inventcraft`
  - Contraseña: `inventcraft`
  - Base de datos: `inventcraft`

### 6. Credenciales de prueba

El seeder crea un usuario de prueba:
- **Email**: `test@test.com`
- **Contraseña**: `password`

---

## Despliegue Local (sin Docker)

### 1. Clonar el repositorio

```bash
git clone https://github.com/CynthiaRubio/Proyecto_InventCraft.git
cd Proyecto_InventCraft
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Instalar dependencias de Node.js

```bash
npm install
```

### 4. Compilar assets

```bash
npm run build
```

### 5. Configurar el entorno

Copia el archivo de ejemplo y configura las variables:

```bash
cp .env.example .env
```

Edita el archivo `.env` con tus configuraciones:

```env
APP_NAME=InventCraft
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventcraft
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# Opcional: API Key de Freesound
FREESOUND_API_KEY=tu_api_key_aqui
```

### 6. Generar clave de aplicación

```bash
php artisan key:generate
```

### 7. Crear la base de datos

Crea una base de datos MySQL llamada `inventcraft` (o el nombre que hayas configurado en `.env`):

```sql
CREATE DATABASE inventcraft CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 8. Ejecutar migraciones

```bash
php artisan migrate
```

### 9. Poblar la base de datos

```bash
php artisan db:seed
```

### 10. Configurar permisos

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 11. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en: http://localhost:8000

---

## Comandos Útiles

### Con Docker

#### Ver logs
```bash
docker-compose logs -f
```

#### Ejecutar comandos artisan
```bash
docker exec inventcraft_app php artisan [comando]
```

#### Acceder al contenedor PHP
```bash
docker exec -it inventcraft_app bash
```

#### Acceder a MySQL
```bash
docker exec -it inventcraft_db mysql -u inventcraft -pinventcraft inventcraft
```

#### Recompilar assets
```bash
docker exec inventcraft_app npm run build
```

#### Detener contenedores
```bash
docker-compose down
```

#### Detener y eliminar volúmenes (incluyendo base de datos)
```bash
docker-compose down -v
```

#### Reconstruir contenedores
```bash
docker-compose up -d --build
```

### Sin Docker

#### Limpiar cachés
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

#### Recompilar assets
```bash
npm run build
```

#### Ejecutar migraciones frescas (elimina y recrea todo)
```bash
php artisan migrate:fresh --seed
```

---

## Configuración Adicional

### Cambiar puertos (Docker)

Si los puertos 8080 o 3306 están ocupados, edita `docker-compose.yml`:

```yaml
nginx:
  ports:
    - "8081:80"  # Cambia 8080 por 8081

db:
  ports:
    - "3307:3306"  # Cambia 3306 por 3307 (si el puerto 3306 está ocupado)
```

### API Key de Freesound (Opcional pero recomendado)

Para habilitar los sonidos ambientales de las zonas, necesitas obtener una API key de Freesound:

**Pasos para obtener tu API key:**

1. Regístrate o inicia sesión en https://freesound.org
2. Ve a tu perfil y accede a "API" o "Applications"
3. Crea una nueva aplicación para obtener tu API key
4. Copia la API key generada
5. Agrega la clave en tu archivo `.env`:

   ```env
   FREESOUND_API_KEY=tu_api_key_aqui
   ```

**Nota importante:**
- Sin esta clave, las zonas funcionarán normalmente pero **sin sonidos ambientales**
- La aplicación **no fallará** si no tienes la API key, simplemente no habrá sonidos
- La API key es gratuita y solo requiere registro en Freesound

---

## Solución de Problemas

### Error: "Port already in use" (Docker)

Cambia los puertos en `docker-compose.yml` como se indica arriba.

### Error: "Permission denied" (Linux/Mac)

```bash
sudo chown -R $USER:$USER storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Error: "Class not found" o problemas de autoload

```bash
# Con Docker
docker exec inventcraft_app composer dump-autoload

# Sin Docker
composer dump-autoload
```

### Error: Problemas con composer.lock o dependencias obsoletas

El script `init.sh` maneja automáticamente estos problemas:
- Si detecta problemas con `composer.lock` (como dependencias obsoletas o incompatibles), actualiza automáticamente las dependencias
- Ignora requisitos de extensiones no necesarias (como MongoDB)
- Verifica que las dependencias se instalaron correctamente antes de continuar

Si el script falla, puedes forzar una actualización manual:
```bash
docker exec inventcraft_app composer update --ignore-platform-req=ext-mongodb
```

### Error: "Database connection refused"

**Con Docker:**
- Verifica que el contenedor `db` esté corriendo: `docker ps`
- Verifica las credenciales en `.env` (deben ser `inventcraft`/`inventcraft`)

**Sin Docker:**
- Verifica que MySQL esté corriendo
- Verifica las credenciales en `.env`
- Verifica que la base de datos exista

### Error: "Vite manifest not found"

```bash
# Con Docker
docker exec inventcraft_app npm run build

# Sin Docker
npm run build
```

### Limpiar todo y empezar de nuevo (Docker)

```bash
docker-compose down -v
docker system prune -a
docker-compose up -d --build
./docker/init.sh
```

### Limpiar todo y empezar de nuevo (Sin Docker)

```bash
php artisan migrate:fresh --seed
npm run build
php artisan config:clear
php artisan cache:clear
```

---

## Notas Importantes

- **Datos de prueba**: El seeder crea un usuario de prueba (`test@test.com` / `password`)
- **Base de datos**: Con Docker, los datos se persisten en un volumen. Si eliminas el volumen (`docker-compose down -v`), perderás todos los datos.
- **Archivos**: Con Docker, los archivos del proyecto se montan como volumen, por lo que los cambios se reflejan inmediatamente.
- **Producción**: Para producción, asegúrate de:
  - Cambiar `APP_DEBUG=false` en `.env`
  - Usar contraseñas seguras
  - Configurar HTTPS
  - Optimizar las configuraciones de PHP y MySQL

---

## Estructura del Proyecto

```
InventCraft_mejorado/
├── app/                    # Código de la aplicación
│   ├── Http/              # Controladores, Middleware, Requests
│   ├── Models/            # Modelos Eloquent
│   ├── Services/          # Lógica de negocio
│   └── ViewModels/        # ViewModels para vistas
├── database/
│   ├── migrations/        # Migraciones de base de datos
│   ├── seeders/          # Seeders para poblar datos
│   └── data/             # Archivos de datos para seeders
├── resources/
│   ├── views/            # Vistas Blade
│   ├── css/              # Estilos CSS
│   └── js/               # JavaScript
├── routes/               # Rutas de la aplicación
├── docker/               # Configuración Docker
│   ├── nginx/           # Configuración Nginx
│   ├── php/             # Configuración PHP
│   └── mysql/           # Configuración MySQL
├── docker-compose.yml    # Orquestación Docker
└── Dockerfile            # Imagen Docker PHP
```

---

## Verificación del Despliegue

Después de desplegar, verifica que todo funcione:

1. Accede a http://localhost:8080 (Docker) o http://localhost:8000 (local)
2. Deberías ver la página de inicio de InventCraft
3. Haz clic en "Regístrate" y crea una cuenta, o usa las credenciales de prueba
4. Verifica que puedas:
   - Iniciar sesión
   - Ver el mapa
   - Explorar zonas
   - Ver inventos y materiales

Si todo funciona correctamente, ¡el despliegue ha sido exitoso! 🎉

---

## Soporte

Si encuentras problemas durante el despliegue, revisa:
1. Los logs: `docker-compose logs -f` (Docker) o `storage/logs/laravel.log` (local)
2. La sección de "Solución de Problemas" arriba
3. Los requisitos del sistema

---

**¡Disfruta jugando InventCraft!** 

