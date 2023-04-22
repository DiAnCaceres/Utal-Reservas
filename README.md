# Utal-Reservas

## Semana 2:
### Solución problema inicial para que a todos los integrantes les corra el proyecto

Seguir los pasos (POR FAVOR EVITAR EL 5):
https://stackoverflow.com/questions/71186895/500-server-error-laravel-project-after-clone-from-github

### Spatie: Laravel-Permission
Paquete que permite manejar permisos de usuarios y roles en la base de datos

¿Cómo instalar?
https://spatie.be/docs/laravel-permission/v5/installation-laravel

composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

Ahora para que migre las tablas necesarias para los usuarios y sus roles:
php artisan migrate 

Luego ir a Basic Usage (https://spatie.be/docs/laravel-permission/v5/basic-usage/basic-usage) y pegar el:

use Spatie\Permission\Traits\HasRoles en el modelo Usuario

### Script inicial para mantener la misma base de dato
Todos tendremos distintas instancias de MySQL donde podremos agregar distintos usuarios que no necesariamente se sincronizarán. Sin embargo, es importante que mantengamos esto en común:

```SQL
INSERT INTO estado_reservas(0,'deshabilitado');
INSERT INTO estado_reservas(1,'habilitado');

INSERT INTO tipo_reservas(1,'sala_estudio');
INSERT INTO tipo_reservas(2,'sala_gimnasio');
INSERT INTO tipo_reservas(3,'implemento');
INSERT INTO tipo_reservas(4,'cancha');
```

Así que cuando clonen el proyecto, recuerden añadir esto (se van al apartado SQL del PHPMyAdmin) y lo ejecutan.
