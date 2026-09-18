# Taydence Framework

Taydence Framework is a small PHP framework built from scratch to understand how frameworks such as Laravel work internally.

## v0.1

The first version provides a minimal HTTP request lifecycle:

**HTTP request → Application → Router → Request → Handler → Response**

### Included

- Application kernel
- GET routes
- POST routes
- Request object
- Query-string and POST input
- Response object
- 404 handling
- PSR-4 autoloading through Composer

## Requirements

- PHP 8.2+
- Composer

## Quick start

```bash
composer install
php -S localhost:8000 -t public
```

Visit http://localhost:8000/

Try:

- `/`
- `/about`
- `/hello?name=Mark`

## Example

```php
use Taydence\Http\Request;

$app->get('/students', function (Request $request) {
    return 'Students';
});
```

## Roadmap

- [x] Application kernel
- [x] GET routes
- [x] POST routes
- [x] Request object
- [x] Response object
- [x] 404 handling
- [ ] Route parameters
- [ ] Middleware
- [ ] Controllers
- [ ] Dependency container
- [ ] Configuration
- [ ] Database layer
- [ ] ORM
- [ ] Validation
- [ ] Authentication
- [ ] CLI tooling
- [ ] Testing utilities

The goal is learning and experimentation, not replacing Laravel.

## License

MIT
