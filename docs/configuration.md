# Configuration Reference

## Full Configuration

```yaml
# config/packages/rate_limiter.yaml
rate_limiter:
    # Default algorithm to use
    default_algorithm: token_bucket  # token_bucket, sliding_window, fixed_window
    
    # Redis connection
    redis:
        client: 'redis://localhost:6379'
        password: null
        
    # Global limits (optional)
    limits:
        api:
            limit: 1000
            window: 3600
        
    # Whitelist (IP addresses that bypass rate limiting)
    whitelist:
        - '127.0.0.1'
        - '192.168.1.0/24'
        
    # Blacklist (IP addresses that are always blocked)
    blacklist:
        - '10.0.0.1'
```

## Algorithm Comparison

| Algorithm | Use Case | Pros | Cons |
|-----------|----------|------|------|
| token_bucket | APIs with burst traffic | Allows bursts, smooth rate | Complex |
| sliding_window | Strict rate compliance | Most accurate | Higher memory |
| fixed_window | High traffic, simple | Efficient, low memory | Boundary spikes |

## Per-Endpoint Configuration

### Using Attributes

```php
use Syeedalireza\RateLimiterBundle\Attribute\RateLimit;

#[RateLimit(limit: 100, window: 3600)]
class ApiController
{
    #[RateLimit(limit: 10, window: 60, algorithm: 'sliding_window')]
    public function sensitiveEndpoint(): Response
    {
        // Limited to 10 requests per minute
    }
}
```

### Cost-Based Limiting

```php
#[RateLimit(limit: 1000, window: 3600, cost: 10)]
public function heavyOperation(): Response
{
    // This endpoint costs 10 tokens per request
}
```

## Environment Variables

```env
REDIS_URL=redis://localhost:6379
REDIS_PASSWORD=your-secret-password
RATE_LIMITER_ENABLED=true
```
