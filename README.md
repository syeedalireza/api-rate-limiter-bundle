# API Rate Limiter Bundle

[![Latest Stable Version](https://poser.pugx.org/syeedalireza/api-rate-limiter-bundle/v/stable)](https://packagist.org/packages/syeedalireza/api-rate-limiter-bundle)
[![License](https://poser.pugx.org/syeedalireza/api-rate-limiter-bundle/license)](https://packagist.org/packages/syeedalireza/api-rate-limiter-bundle)
[![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue)](https://www.php.net/)

Enterprise-grade **API Rate Limiting** for Symfony applications with multiple algorithms, distributed support via Redis, and comprehensive analytics.

## 🚀 Features

- **Multiple Algorithms**: Token Bucket, Sliding Window, Fixed Window, Leaky Bucket
- **Distributed Rate Limiting**: Redis-based for microservices
- **Flexible Limits**: Per IP, User, API Key, or Endpoint
- **RFC Compliance**: RateLimit-* HTTP headers
- **Analytics**: Real-time metrics and monitoring
- **PHP 8 Attributes**: Modern configuration style
- **Production Ready**: Battle-tested, optimized Lua scripts

## 📦 Installation

```bash
composer require syeedalireza/api-rate-limiter-bundle
```

## 🎯 Quick Start

### 1. Configure

```yaml
# config/packages/rate_limiter.yaml
rate_limiter:
    default_algorithm: token_bucket
    redis:
        client: 'redis://localhost:6379'
    limits:
        api:
            limit: 100
            window: 3600  # 1 hour
```

### 2. Use Attributes

```php
use Syeedalireza\RateLimiterBundle\Attribute\RateLimit;

#[RateLimit(limit: 100, window: 3600)]
class ApiController extends AbstractController
{
    #[Route('/api/users')]
    #[RateLimit(limit: 10, window: 60, key: 'ip')]
    public function getUsers(): JsonResponse
    {
        // Max 10 requests per minute per IP
    }
}
```

### 3. Check Limits Programmatically

```php
use Syeedalireza\RateLimiterBundle\Service\RateLimiter;

public function __construct(
    private RateLimiter $rateLimiter
) {}

public function someAction(): Response
{
    $status = $this->rateLimiter->check('user:123', limit: 100, window: 3600);
    
    if (!$status->isAllowed()) {
        throw new TooManyRequestsHttpException(
            $status->getRetryAfter(),
            'Rate limit exceeded'
        );
    }
}
```

## 📊 Algorithms

### Token Bucket
Best for burst tolerance with steady rate.

```php
#[RateLimit(algorithm: 'token_bucket', limit: 100, window: 60)]
```

### Sliding Window
Most accurate, prevents boundary issues.

```php
#[RateLimit(algorithm: 'sliding_window', limit: 100, window: 60)]
```

### Fixed Window
Simple, efficient, but has boundary spikes.

```php
#[RateLimit(algorithm: 'fixed_window', limit: 100, window: 60)]
```

## 🔧 Advanced Usage

### Custom Cost per Endpoint

```php
#[RateLimit(limit: 1000, window: 3600, cost: 10)]
public function heavyOperation(): Response
{
    // This request costs 10 tokens
}
```

### Whitelist/Blacklist

```yaml
rate_limiter:
    whitelist:
        - '192.168.1.100'
        - '10.0.0.0/8'
    blacklist:
        - '185.220.101.0/24'  # Tor exit nodes
```

### Multiple Limits

```php
#[RateLimit(limit: 10, window: 1)]     // 10 per second
#[RateLimit(limit: 100, window: 60)]   // 100 per minute
#[RateLimit(limit: 1000, window: 3600)] // 1000 per hour
public function api(): Response {}
```

## 📈 Monitoring

```php
$metrics = $this->rateLimiter->getMetrics('user:123');

echo $metrics->getRequestCount();
echo $metrics->getRemainingTokens();
echo $metrics->getResetTime();
```

## 🐳 Docker Support

Included Redis setup for development:

```bash
docker-compose up -d
```

## 📚 Documentation

- [Installation Guide](docs/installation.md)
- [Configuration Reference](docs/configuration.md)
- [Algorithms Comparison](docs/algorithms.md)
- [Performance Benchmarks](docs/benchmarks.md)

## 🧪 Testing

```bash
composer test           # Run tests
composer benchmark      # Run performance benchmarks
composer quality        # All quality checks
```

## 🤝 Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md)

## 📄 License

MIT License - see [LICENSE.md](LICENSE.md)

## 👨‍💻 Author

**Alireza Aminzadeh**
- Email: alireza.aminzadeh@hotmail.com
- GitHub: [@syeedalireza](https://github.com/syeedalireza)
