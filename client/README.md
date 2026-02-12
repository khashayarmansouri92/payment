<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Project

This Laravel project is fully Dockerized with Docker Compose for local development. It includes Swagger/OpenAPI documentation, MySQL, Redis, and phpMyAdmin.

### Features

- ✅ Dockerized with Docker Compose
- ✅ Swagger/OpenAPI Documentation (L5-Swagger)
- ✅ Makefile for quick commands
- ✅ MySQL 8.0 Database
- ✅ Redis for Cache & Queue
- ✅ phpMyAdmin for Database Management
- ✅ PHP 8.4 with all required extensions
- ✅ Nginx web server
- ✅ Supervisor for process management

## Prerequisites

- Docker (version 20.10 or higher)
- Docker Compose (version 2.0 or higher)
- Make (optional but recommended)

### Check Prerequisites

```bash
# Check Docker installation
docker --version

# Check Docker Compose installation
docker compose version

# Check Make installation (optional)
make --version
```

## Quick Start

### Method 1: Using Makefile (Recommended)

```bash
# Complete installation with one command
make install

# Or step by step:
make build          # Build containers
make up             # Start containers
make composer-install # Install PHP dependencies
make npm-install    # Install Node dependencies
make key            # Generate application key
make migrate-seed   # Run migrations and seed
make swagger-generate # Generate Swagger documentation
```

### Method 2: Using Docker Compose Directly

```bash
# Build and start containers
docker compose -f docker/docker-compose.yml up -d --build

# Install PHP dependencies
docker compose -f docker/docker-compose.yml exec app composer install

# Install Node dependencies
docker compose -f docker/docker-compose.yml exec app npm install

# Generate application key
docker compose -f docker/docker-compose.yml exec app php artisan key:generate

# Run migrations and seed
docker compose -f docker/docker-compose.yml exec app php artisan migrate --seed

# Generate Swagger documentation
docker compose -f docker/docker-compose.yml exec app php artisan l5-swagger:generate
```

## Services & Ports

| Service | Container Name | Host Port | Container Port | Description |
|---------|---------------|-----------|----------------|-------------|
| Laravel App | `laravel_app` | 8002 | 80 | Main application |
| MySQL | `laravel_mysql` | 3308 | 3306 | Database |
| Redis | `laravel_redis` | 6380 | 6379 | Cache & Queue |
| phpMyAdmin | `laravel_phpmyadmin` | 8082 | 80 | Database management |

## Access URLs

- **Application**: http://localhost:8002
- **Swagger UI**: http://localhost:8002/api/documentation
- **phpMyAdmin**: http://localhost:8082
  - Username: `laravel`
  - Password: `root`

## Environment Variables

The `.env` file should be configured with the following important settings:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8002

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=root

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

L5_SWAGGER_CONST_HOST=http://localhost:8002

# Docker Ports
APP_PORT=8002
DB_PORT=3308
REDIS_PORT=6380
PHPMYADMIN_PORT=8082
```

**Important Notes:**
- `DB_HOST` must be `mysql` (not `localhost`) when connecting from the app container
- `DB_PORT` in `.env` should be `3306` (container port), not `3308` (host port)
- Ports can be customized in `.env` to avoid conflicts with other projects

## Useful Commands

### Using Makefile

```bash
make help          # Show all available commands
make up            # Start containers
make down          # Stop containers
make restart       # Restart containers
make logs          # Show container logs
make shell         # Enter app container shell

# Dependencies
make composer-install # Install Composer dependencies
make composer-update # Update Composer dependencies
make composer-dump   # Update autoload
make npm-install     # Install NPM dependencies
make npm-build       # Build frontend files
make npm-dev         # Run Vite in development mode

# Laravel
make key            # Generate application key
make migrate        # Run migrations
make migrate-fresh  # Drop and re-run migrations
make migrate-rollback # Rollback migrations
make seed           # Run seeders
make migrate-seed   # Run migrations and seed

# Cache
make cache-clear    # Clear all cache
make cache          # Build cache
make optimize       # Optimize application

# Swagger
make swagger-generate # Generate Swagger documentation
make swagger        # Generate and view Swagger documentation

# Testing
make test           # Run tests
make test-coverage  # Run tests with coverage

# Database
make mysql          # Enter MySQL CLI
make redis-cli      # Enter Redis CLI

# Queue
make queue-work     # Run queue worker
make queue-listen   # Run queue listener

# Other
make tinker         # Run Tinker
make schedule       # Run scheduled tasks
make permissions    # Set file permissions
make ps             # Show container status
make clean          # Complete cleanup (containers and volumes)
make fresh          # Drop and recreate database
```

### Using Docker Compose

```bash
# Container management
docker compose -f docker/docker-compose.yml up -d              # Start
docker compose -f docker/docker-compose.yml down               # Stop
docker compose -f docker/docker-compose.yml restart            # Restart
docker compose -f docker/docker-compose.yml logs -f           # Show logs
docker compose -f docker/docker-compose.yml ps                 # Show status

# Execute commands
docker compose -f docker/docker-compose.yml exec app sh        # Enter shell
docker compose -f docker/docker-compose.yml exec app php artisan migrate
docker compose -f docker/docker-compose.yml exec app composer install
```

## Step-by-Step Setup

### Step 1: Configure .env File

The `.env` file should exist in the project root. If it doesn't:

```bash
# Copy from .env.example
cp .env.example .env
```

Ensure the most important settings are configured (see Environment Variables section above).

### Step 2: Start Containers

```bash
# With Makefile
make up

# Or with Docker Compose
docker compose -f docker/docker-compose.yml up -d
```

### Step 3: Install Dependencies

```bash
# Install Composer packages
make composer-install
# or
docker compose -f docker/docker-compose.yml exec app composer install

# Install NPM packages
make npm-install
# or
docker compose -f docker/docker-compose.yml exec app npm install
```

### Step 4: Laravel Setup

```bash
# Generate application key
make key
# or
docker compose -f docker/docker-compose.yml exec app php artisan key:generate

# Run migrations
make migrate
# or
docker compose -f docker/docker-compose.yml exec app php artisan migrate

# Run seeders (optional)
make seed
# or
docker compose -f docker/docker-compose.yml exec app php artisan db:seed
```

### Step 5: Generate Swagger Documentation

```bash
make swagger-generate
# or
docker compose -f docker/docker-compose.yml exec app php artisan l5-swagger:generate
```

## Daily Workflow

```bash
# Morning: Start containers
make up

# Work with project
# Code changes are automatically applied (volume mounted)

# Run new migrations
make migrate

# Run tests
make test

# View logs if needed
make logs

# Evening: Stop containers (optional)
make down
```

## Common Issues and Solutions

### Issue: Port Already in Use

If you get port conflict errors:

```bash
# Check used ports
lsof -i :8002
lsof -i :3308
lsof -i :6380
lsof -i :8082

# Change ports in .env
APP_PORT=8003
DB_PORT=3309
REDIS_PORT=6381
PHPMYADMIN_PORT=8083
```

### Issue: File Permission Errors

```bash
make permissions
# or
docker compose -f docker/docker-compose.yml exec app chown -R www-data:www-data /var/www/html/storage
docker compose -f docker/docker-compose.yml exec app chmod -R 755 /var/www/html/storage
docker compose -f docker/docker-compose.yml exec app chmod -R 755 /var/www/html/bootstrap/cache
```

### Issue: Database Connection

If you can't connect to the database:

```bash
# Check MySQL container status
docker compose -f docker/docker-compose.yml ps mysql

# Check MySQL logs
docker compose -f docker/docker-compose.yml logs mysql

# Ensure .env settings
DB_HOST=mysql  # Important: must be 'mysql' not 'localhost'
DB_PORT=3306   # Container port, not host port
```

### Issue: Old Cache

If you see old configuration or cached data:

```bash
make cache-clear
# or
docker compose -f docker/docker-compose.yml exec app php artisan cache:clear
docker compose -f docker/docker-compose.yml exec app php artisan config:clear
docker compose -f docker/docker-compose.yml exec app php artisan route:clear
docker compose -f docker/docker-compose.yml exec app php artisan view:clear
```

### Issue: Container Build Errors

If you encounter build errors:

```bash
# Clean build
make build
# or
docker compose -f docker/docker-compose.yml build --no-cache
```

## Complete Restart

If you encounter issues and want to start fresh:

```bash
# Stop and remove everything (including volumes)
make clean

# or manually
docker compose -f docker/docker-compose.yml down -v
docker system prune -f

# Then reinstall
make install
```

## Project Structure

```
project/
├── docker/
│   ├── Dockerfile              # PHP-FPM + Nginx image
│   ├── docker-compose.yml      # Docker Compose configuration
│   ├── .dockerignore           # Docker ignore file
│   ├── nginx/
│   │   └── default.conf        # Nginx configuration
│   ├── php/
│   │   └── local.ini           # PHP configuration
│   └── supervisor/
│       └── supervisord.conf    # Supervisor configuration
├── app/                        # Laravel application
├── config/                     # Configuration files
├── routes/
│   └── api.php                # API routes with Swagger annotations
├── storage/
│   └── api-docs/              # Generated Swagger documentation
├── .env                        # Environment variables
├── Makefile                    # Make commands
└── README.md                   # This file
```

## Important Notes

1. **This setup is optimized for local development only**
2. **Code changes are automatically applied** (volume mounted)
3. **For changes in Dockerfile or docker-compose.yml, you need to rebuild**
4. **Check `.env` file that `APP_ENV=local` and `APP_DEBUG=true`**
5. **Database host must be `mysql` (container name) not `localhost`**
6. **Inside container, use port 3306 for MySQL, not 3308**

## Swagger Documentation

The project uses L5-Swagger for API documentation. After generating documentation:

1. Access Swagger UI at: http://localhost:8002/api/documentation
2. API routes are annotated in `routes/api.php`
3. Regenerate documentation after adding new routes:
   ```bash
   make swagger-generate
   ```

## Database Access

### Using phpMyAdmin

1. Open http://localhost:8082
2. Login with:
   - Server: `mysql`
   - Username: `laravel`
   - Password: `root`

### Using MySQL CLI

```bash
make mysql
# or
docker compose -f docker/docker-compose.yml exec mysql mysql -u laravel -p
```

### Using Redis CLI

```bash
make redis-cli
# or
docker compose -f docker/docker-compose.yml exec redis redis-cli
```

## Testing

```bash
# Run all tests
make test

# Run tests with coverage
make test-coverage

# Or manually
docker compose -f docker/docker-compose.yml exec app php artisan test
```

## Queue Workers

```bash
# Run queue worker
make queue-work

# Run queue listener
make queue-listen

# Or manually
docker compose -f docker/docker-compose.yml exec app php artisan queue:work
```

## Troubleshooting

### Check Container Status

```bash
make ps
# or
docker compose -f docker/docker-compose.yml ps
```

### View Logs

```bash
# All containers
make logs

# Specific container
docker compose -f docker/docker-compose.yml logs app
docker compose -f docker/docker-compose.yml logs mysql
```

### Enter Container

```bash
make shell
# or
docker compose -f docker/docker-compose.yml exec app sh
```

### Issue: Docker Desktop File Sharing (macOS)

If you get "mounts denied" errors on macOS, Docker Desktop needs access to the project directory:

1. Open Docker Desktop application
2. Click on the gear icon (⚙️) in the top right corner
3. Go to **Resources** → **File Sharing**
4. Click the **"+"** button to add a new path
5. Navigate to or type your project path (e.g., `/Applications/MAMP/htdocs/project/project`)
6. Click **Apply & Restart**

**Note:** By default, Docker Desktop shares `/Users`, `/Volumes`, `/private`, and `/tmp`. If your project is outside these paths, you need to add it manually.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
