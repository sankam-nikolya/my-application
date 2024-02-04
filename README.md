# My Application

## Розвертаємо проект

1. Середовище

```bash
docker compose -f docker-compose.yml
```

2. Створюємо БД з назвою 'test-app', кодування 'utf8mb4' або правимо підключення до БД тут '

```bash
common/config/main-local.php
```

3. Заходимо в контейнер

```bash
docker exec -it test-app /bin/bash
```

4. Встановлюємо залежності

```bash
composer install
```

5. Копіюємо файли конфігів

```bash
php init
```

7. Запускаємо виконання міграці1

```bash
php yii migrate
```

## Доступні домени:

- http://test-app.localhost/ - проект
- http://pma.test-app.localhost/ - phpMyAdmin
- http://mail.test-app.localhost/ - MailCatcher
