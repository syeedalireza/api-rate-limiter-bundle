<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Syeedalireza\RateLimiterBundle\Service\RateLimiter;
use Syeedalireza\RateLimiterBundle\Storage\RedisStorage;
use Syeedalireza\RateLimiterBundle\Strategy\TokenBucketStrategy;
use Predis\Client;

// Setup Redis
$redis = new Client(['scheme' => 'tcp', 'host' => '127.0.0.1', 'port' => 6379]);
$storage = new RedisStorage($redis);
$strategy = new TokenBucketStrategy($storage);
$rateLimiter = new RateLimiter($strategy);

echo "=== API Rate Limiter Example ===\n\n";

// Check rate limit for user
$userId = 'user123';
for ($i = 1; $i <= 5; $i++) {
    $status = $rateLimiter->check($userId, limit: 3, window: 60);
    
    echo "Request {$i}:\n";
    echo "  Allowed: " . ($status->isAllowed() ? 'YES' : 'NO') . "\n";
    echo "  Remaining: {$status->remaining}\n";
    echo "  Limit: {$status->limit}\n";
    
    if (!$status->isAllowed()) {
        echo "  Retry after: {$status->getRetryAfter()} seconds\n";
    }
    echo "\n";
}

echo "✅ Example completed!\n";
