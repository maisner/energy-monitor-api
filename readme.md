### ENV variables
```dotenv
MYSQL_HOST=mysql
MYSQL_NAME=energy-monitor
MYSQL_USER=energy-monitor
MYSQL_PASS=123
```

### Production docker build
```bash
docker build -t energy-monitor-api --build-arg DB_HOST=innodb.endora.cz:3306 --build-arg DB_NAME=energymonitor --build-arg DB_USER=energy --build-arg DB_PASS='Abc123_#' .
```
