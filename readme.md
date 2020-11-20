## Docker
- Primeira vez carregar dados do DB opencart apos iniciar o docker
```bash
docker exec -i mysql-opencart mysql -uroot -p1234 < db/opencartclean.sql
```

- Iniciar Docker
```bash
docker-compose up -d
```

- Parar Docker
```bash
docker-compose down
```