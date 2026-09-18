# Taydence Framework

Taydence Framework is a small PHP framework built from scratch to understand how frameworks such as Laravel work internally.

## v0.4 — Dependency Injection Container

The framework now includes a small dependency injection container.

Instead of the Router directly constructing controllers with `new`, the Container resolves them. It can also automatically resolve class-typed constructor dependencies.

### Example

A controller can declare a dependency:

```php
final class UserController
{
    public function __construct(private UserService $users)
    {
    }
}
```

The framework can resolve `UserService` automatically when the controller is created.

You can also register your own bindings:

```php
$app->bind(UserService::class, fn () => new UserService());
```

For shared instances, use a singleton:

```php
$app->singleton(Database::class, fn () => new Database());
```

### Container capabilities

- Resolve concrete classes automatically
- Resolve class-typed constructor dependencies
- Register custom bindings
- Register singleton bindings
- Register existing instances
- Resolve controllers through the container

### Included

- Application kernel
- GET and POST routes
- Dynamic route parameters
- Controller handlers
- Request object
- Query-string and POST input
- Response object
- JSON responses
- Immutable response headers
- Application-level middleware
- Middleware pipeline
- Dependency injection container
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

The example application registers `PoweredByMiddleware`, so responses include an `X-Powered-By: Taydence Framework` header.

## Architecture

**HTTP request → Application → Middleware Pipeline → Router → Container → Controller/Handler → Response**

## Roadmap

- [x] Application kernel
- [x] GET routes
- [x] POST routes
- [x] Request object
- [x] Response object
- [x] JSON responses
- [x] Route parameters
- [x] Controller handlers
- [x] Middleware
- [x] Dependency container
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