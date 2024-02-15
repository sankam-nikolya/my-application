# My Application

## Розвертаємо проект

1. Середовище

```bash
docker compose -f docker-compose.yml
```

2. Заходимо в контейнер

```bash
docker exec -it test-app /bin/bash
```

3. Встановлюємо залежності

```bash
composer install
```

4. Копіюємо файли конфігів

```bash
php init
```

5. Запускаємо виконання міграцій

```bash
php yii migrate
```

## Доступні домени:

- http://test-app.localhost/ - проект
- http://pma.test-app.localhost/ - phpMyAdmin
- http://mail.test-app.localhost/ - MailCatcher
