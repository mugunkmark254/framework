# Taydence Framework

Taydence Framework is a small PHP framework built from scratch to understand how frameworks such as Laravel work internally.

## v0.3 — Middleware

The framework now supports application-level middleware.

Middleware sits between the incoming HTTP request and the router. It can inspect or modify a request, stop the request early, or modify the response returned by the application.

### Example

Register middleware in the application:

```php
use Taydence\Middleware\PoweredByMiddleware;

$app->middleware(new PoweredByMiddleware());
```

A middleware can wrap the next step in the request lifecycle:

```php
public function handle(Request $request, callable $next): Response
{
    return $next($request)->withHeader('X-Powered-By', 'Taydence Framework');
}
```

The resulting flow is:

**HTTP request → Middleware → Router → Controller/Handler → Response → Middleware → HTTP response**

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

**HTTP request → Application → Middleware Pipeline → Router → Controller/Handler → Response**

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
