# Installation Guide

## Requirements
- PHP 8.2+
- Symfony 6.4 or 7.x
- Redis (for distributed rate limiting)

## Installation

```bash
composer require syeedalireza/api-rate-limiter-bundle
```

## Enable Bundle

```php
// config/bundles.php
return [
    // ...
    Syeedalireza\RateLimiterBundle\RateLimiterBundle::class => ['all' => true],
];
```

## Configuration

```yaml
# config/packages/rate_limiter.yaml
rate_limiter:
    default_algorithm: token_bucket
    redis:
        client: 'redis://localhost:6379'
```

## Verify Installation

```bash
php bin/console debug:container | grep rate_limiter
```
