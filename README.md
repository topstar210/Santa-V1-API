# Laravel Santa-v1-API

## Initial Setup
```
git clone https://github.com/topstar210/Santa-V1-API.git
cd Santa-V1-API
cp .env.example .env
composer update
php artisan migrate:refresh --seed
php artisan serve
```

### Dummy Credentials for user:
email: test@example.com
password: 123456


1. Login with given credentials or you can register
2. Set bearer token as Authorization header
3. Hit API

