# BackEndHLB
BackEnd para la aplicación del Hospital León Becerra
# Manual Instalacion (Linux/Windows)
1. Instalar Laragon Full de aquí: https://laragon.org/download/
1. cd BackEnd-HLB/
2. composer install
3. crear instancia de base de datos: backendhlb
4. php artisan migrate
5. php artisan db:seed
6. comprobar si se crearon y llenaron las tablas de la instancia
7. php artisan serve
8. consumir la api
