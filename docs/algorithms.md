# Rate Limiting Algorithms

## Overview

This bundle provides 4 different rate limiting algorithms, each with different characteristics.

## 1. Token Bucket

**Best for:** Allowing bursts while maintaining average rate

**How it works:**
- Tokens are added to a bucket at a fixed rate
- Each request consumes tokens
- Requests are allowed if enough tokens exist

**Pros:**
- Allows natural bursts
- Smooth rate limiting

**Cons:**
- More complex than fixed window

**Usage:**
```php
#[RateLimit(algorithm: 'token_bucket', limit: 100, window: 60)]
```

## 2. Sliding Window

**Best for:** Most accurate rate limiting

**How it works:**
- Tracks individual request timestamps
- Counts requests in a sliding time window
- No boundary issues

**Pros:**
- Most accurate
- No burst at boundaries

**Cons:**
- Higher memory usage

**Usage:**
```php
#[RateLimit(algorithm: 'sliding_window', limit: 100, window: 60)]
```

## 3. Fixed Window

**Best for:** Simple, efficient rate limiting

**How it works:**
- Divides time into fixed windows
- Counts requests per window
- Resets counter at window boundary

**Pros:**
- Simple and efficient
- Low memory usage

**Cons:**
- Boundary spike issues

**Usage:**
```php
#[RateLimit(algorithm: 'fixed_window', limit: 100, window: 60)]
```

## Performance Comparison

| Algorithm | Memory | Accuracy | Burst Tolerance |
|-----------|--------|----------|-----------------|
| Token Bucket | Medium | High | High |
| Sliding Window | High | Very High | Medium |
| Fixed Window | Low | Medium | Low |

## Choosing the Right Algorithm

- **API with burst traffic**: Token Bucket
- **Strict rate compliance**: Sliding Window
- **High traffic, efficiency critical**: Fixed Window
