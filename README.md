# 🚀 Guía de Despliegue de InventCraft

Esta guía te ayudará a desplegar InventCraft en tu entorno local o servidor.

> **¿Buscas las instrucciones del juego?** Consulta el archivo [README_JUEGO.md](README_JUEGO.md) para conocer las reglas, mecánicas y objetivos del juego.

## Despliegue con Docker

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

### 4. API Key de Freesound (Opcional pero recomendado)

Para habilitar los sonidos ambientales de las zonas, necesitas obtener una API key de Freesound:

**Pasos para obtener tu API key:**

1. Pídela aquí en https://freesound.org/apiv2/apply
2. Agrega la clave en tu archivo `.env`:

   ```env
   FREESOUND_API_KEY=tu_api_key_aqui
   ```

**Nota importante:**
- Sin esta clave, las zonas funcionarán normalmente pero **sin sonidos ambientales**
- La aplicación **no fallará** si no tienes la API key, simplemente no habrá sonidos
- La API key es gratuita y solo requiere registro en Freesound

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

## Comandos Útiles

### Con Docker

#### Ver logs
```bash
docker compose logs -f
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
docker compose down
```

#### Detener y eliminar volúmenes (incluyendo base de datos)
```bash
docker compose down -v
```

#### Reconstruir contenedores
```bash
docker compose up -d --build
```

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


## Solución de Problemas

### Error: "Port already in use" (Docker)

Cambia los puertos en `docker-compose.yml` como se indica arriba.


### Error: "Database connection refused"

**Con Docker:**
- Verifica que el contenedor `db` esté corriendo: `docker ps`
- Verifica las credenciales en `.env` (deben ser `inventcraft`/`inventcraft`)


### Error: "Vite manifest not found"

```bash
# Con Docker
docker exec inventcraft_app npm run build
```

### Limpiar todo y empezar de nuevo

```bash
docker-compose down -v
docker system prune -a
docker-compose up -d --build
./docker/init.sh
```

## Notas Importantes

- **Datos de prueba**: El seeder crea un usuario de prueba (`test@test.com` / `password`)
- **Base de datos**: Con Docker, los datos se persisten en un volumen. Si eliminas el volumen (`docker-compose down -v`), perderás todos los datos.
- **Archivos**: Con Docker, los archivos del proyecto se montan como volumen, por lo que los cambios se reflejan inmediatamente.
- **Producción**: Para producción, asegúrate de:
  - Cambiar `APP_DEBUG=false` en `.env`
  - Usar contraseñas seguras
  - Configurar HTTPS
  - Optimizar las configuraciones de PHP y MySQL


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

**¡Disfruta jugando InventCraft!** 

