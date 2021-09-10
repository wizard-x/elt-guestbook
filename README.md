# Map Suggest

## Prerequisites
```
Docker
Docker Compose
```

## Project setup
```
git clone https://github.com/wizard-x/elt-guestbook.git
```

### Start dev-server
```
docker-compose up
```

### Update packages
```
docker-compose exec backend composer install
docker-compose exec backend npm install
docker-compose exec backend npm run build
```

### Run unit tests
```
docker-compose exec backend ./bin/phpunit
```

### View tests coverage
```
docker-compose exec backend ./bin/phpunit --coverage-text
```

### Shell
```
docker-compose exec backend /bin/sh
```

### Access to ...
```
web (vue)   - http://127.0.0.1:8000
```
```
php (fcgi)  - http://127.0.0.1:9000
```
