## Docker
- Primeira vez carregar dados do DB opencart apos iniciar o docker
```bash
docker exec -i mysql-opencart mysql -uroot -p1234 < db/opencartclean.sql
docker exec -i mysql-sso mysql -uroot -p1234 < db/user.sql
docker exec -i mysql-sso mysql -uroot -p1234 < db/customer.sql
docker exec -i mysql-sso mysql -uroot -p1234 < db/customerExtras.sql
docker exec -i mysql-sso mysql -uroot -p1234 < db/address.sql
docker exec -i mysql-sso mysql -uroot -p1234 < db/addressExtra.sql
```

- Iniciar Docker
```bash
docker-compose up -d
```

- Parar Docker
```bash
docker-compose down
```

- Reiniciar Nginx
```bash
docker exec -it nginx-opencart service nginx restart
```