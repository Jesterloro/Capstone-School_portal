!!!Required to seed all of the Department first after the first migration!!!


**First Instalaltion**
composer update
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan db:seed --class=DepartmentSeeder
" " *the rest of the seeders
