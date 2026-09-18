# Taydence Framework

Taydence Framework is a small PHP framework built from scratch to understand how frameworks such as Laravel work internally.

## v0.6 — Environment Variables & `.env`

The framework now supports local environment variables through a `.env` file.

Sensitive or environment-specific values should stay outside the committed source code. The repository includes `.env.example` as a safe template, while `.env` is ignored by Git.

### Example

Copy `.env.example` to `.env` and set local values:

```text
APP_NAME="Taydence Framework"
APP_ENV=local
APP_DEBUG=true

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=taydence
DB_USERNAME=root
DB_PASSWORD=secret
```

Read values with:

```php
env('APP_NAME');
env('APP_DEBUG', false);
```

The configuration layer can consume those values:

```php
return [
    'name' => env('APP_NAME', 'Taydence Framework'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
];
```

### Environment capabilities

- Load `.env` values
- Ignore blank lines and comments
- Support quoted values
- Cast booleans, nulls, integers, and floats
- Provide defaults
- Keep `.env` out of Git
- Provide `.env.example` for project setup
## v0.5 — Configuration

The framework now includes a small configuration system loaded from PHP files.

### Example

Create a configuration file:

```php
return [
    'name' => 'Taydence Framework',
    'env' => 'local',
    'debug' => true,
];
```

Load it when creating the application:

```php
$config = Config::fromFile(__DIR__ . '/../config/app.php');
$app = new Application($config);
```

Read values through the application:

```php
$app->config('name');
$app->config('debug', false);
```

Nested configuration is supported with dot notation, such as `database.host`.

### Configuration capabilities

- Load configuration from PHP files
- Read nested values with dot notation
- Provide default values
- Check whether a key exists
- Access the complete configuration array
- Inject `Config` through the dependency container

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
- [x] Configuration
- [ ] Database layer
- [ ] ORM
- [ ] Validation
- [ ] Authentication
- [ ] CLI tooling
- [ ] Testing utilities

The goal is learning and experimentation, not replacing Laravel.

## License

MIT