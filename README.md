# Aplicación Web CRISTAMAR - Cotizador de Eventos (Bogotá y Sabana)

Aplicación web desarrollada en **Laravel 12** sobre **Ubuntu + Docker + Lando** para la simulación en tiempo real de presupuestos de eventos y gestión de solicitudes.

## Secuencia de arranque en Ubuntu + Lando
1. `cp .env.example .env`
2. `lando start`
3. `lando composer install`
4. `lando artisan key:generate`
5. `lando artisan migrate --seed`
6. `lando npm install && lando npm run build`

## Usuarios de prueba (Seeders)
- **Rol Administrador:** `admin@cristamar.com` / `AdminCristamar2026*`
- **Rol Cliente:** `camila@empresa.com` / `Cliente2026*`
