## Docker
- Primeira vez carregar dados do DB opencart apos iniciar o docker
```bash
docker exec -i mysql-opencart mysql -uroot -p1234 < db/opencart.sql
docker exec -i mysql-sso mysql -uroot -p1234 < db/sso.sql
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

- Backup de bases
```bash
docker exec -i (container da base) mysqldump -uroot -p1234 (nome da base que quer exportar) > (nome do arquivo).sql
```