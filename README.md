# Fuffy

## Commands

### Psalm
```
./vendor/bin/psalm
```

### Tests
```
php bin/phpunit
```

### Php-cs-fixer
```
php ./vendor/bin/php-cs-fixer fix ./src
php ./vendor/bin/php-cs-fixer fix ./tests
```

### Fixtures
```
php bin/console --env=test doctrine:fixtures:load
```
or
```
php bin/console doctrine:fixtures:load
```

### Docker
```
docker-compose up
```
php container
```
docker exec -it fuffy_php /bin/bash
```