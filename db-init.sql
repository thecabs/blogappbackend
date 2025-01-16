CREATE USER 'laravel'@'%' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON test_database.* TO 'laravel'@'%';
FLUSH PRIVILEGES;
