CREATE USER 'laravel_user'@'%' IDENTIFIED BY 'laravel_user';
CREATE DATABASE IF NOT EXISTS laravel;
GRANT ALL PRIVILEGES ON laravel.* TO 'laravel_user'@'%';
FLUSH PRIVILEGES;
