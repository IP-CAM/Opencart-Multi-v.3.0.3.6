## Docker
First time loading data from opencart DB after starting docker 
```bash
docker exec -i mysql-opencart mysql -uroot -p1234 < db/opencart.sql
docker exec -i mysql-sso mysql -uroot -p1234 < db/sso.sql
```

- Iniciate Docker
```bash
docker-compose up -d
```

- Stop Docker 
```bash
docker-compose down
```

- Restart Nginx 
```bash
docker exec -it nginx-opencart service nginx restart
```

- Base backup 
```bash
docker exec -i (container da base) mysqldump -uroot -p1234 (nome da base que quer exportar) > (nome do arquivo).sql
```
