# Taydence Framework

Taydence Framework is a small PHP framework built from scratch to understand how frameworks such as Laravel work internally.

## v0.2 — Route Parameters & Controllers

The framework now supports dynamic route parameters and controller handlers.

### Example

```php
use Taydence\Controllers\HomeController;

$app->get('/', [HomeController::class, 'index']);
$app->get('/users/{id}', fn (Request $request, string $id) => Response::json([
    'id' => $id,
]));
```

A request to `/users/42` passes `42` into the route handler.

### Included

- Application kernel
- GET and POST routes
- Dynamic route parameters
- Controller handlers
- Request object
- Query-string and POST input
- Response object
- JSON responses
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
- `/hello/Mark`
- `/users/42`

## Architecture

**HTTP request → Application → Router → Controller/Handler → Response**

## Roadmap

- [x] Application kernel
- [x] GET routes
- [x] POST routes
- [x] Request object
- [x] Response object
- [x] JSON responses
- [x] Route parameters
- [x] Controller handlers
- [ ] Middleware
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
