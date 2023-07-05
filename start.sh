#!/bin/bash

docker-compose up -d
sleep 10
# Generate RSA keys in the specified folder
docker exec -it streaming_app openssl genrsa -out /var/www/html/storage/Crypt/private.key 2048
docker exec -it streaming_app openssl rsa -in /var/www/html/storage/Crypt/private.key -pubout -out /var/www/html/storage/Crypt/public.key

docker exec -it streaming_app chmod 600 /var/www/html/storage/Crypt/private.key
docker exec -it streaming_app chmod 600 /var/www/html/storage/Crypt/public.key

# Copy .env.example to .env
docker exec -it streaming_app cp /var/www/html/.env.example /var/www/html/.env

# Run Doctrine Migrations
docker exec -it streaming_app /var/www/html/vendor/bin/doctrine-migrations migrations:migrate -q -n
sleep 5
# Seed the database
docker exec -i streaming_db mysql -u root -proot -e "USE streaming_backend; INSERT INTO oauth_clients (identifier, name, client_id, client_secret, redirect_uri, provider, created_at, updated_at) VALUES ('app', 'app', '123', '123', null, null, '2023-07-05 18:24:01', '2023-07-05 18:24:05');"

#attact console
docker exec -it streaming_app ash
