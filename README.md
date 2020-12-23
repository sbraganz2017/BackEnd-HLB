# BackEndHLB
BackEnd para la aplicación del Hospital León Becerra
# Manual Instalacion (Linux/Windows)
1. Instalar Laragon Full de aquí: https://laragon.org/download/
2. git clone https://github.com/sbraganz2017/BackEnd-HLB.git
3. cd BackEnd-HLB/
4. composer install
5. crear instancia de base de datos: backendhlb
6. php artisan migrate
7. php artisan db:seed
8. comprobar si se crearon y llenaron las tablas de la instancia
9. php artisan serve
10. consumir la api
