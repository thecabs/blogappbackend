CREATE USER 'laravel'@'%' IDENTIFIED BY '';
CREATE DATABASE IF NOT EXISTS laravel;
GRANT ALL PRIVILEGES ON laravel.* TO 'root'@'%';
FLUSH PRIVILEGES;
