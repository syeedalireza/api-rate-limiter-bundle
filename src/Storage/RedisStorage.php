<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Storage;

use Predis\ClientInterface;

final class RedisStorage implements StorageInterface
{
    public function __construct(
        private readonly ClientInterface $redis,
    ) {
    }

    public function get(string $key): ?array
    {
        $data = $this->redis->get($key);

        return $data ? json_decode($data, true) : null;
    }

    public function set(string $key, array $value, int $ttl): void
    {
        $this->redis->setex($key, $ttl, json_encode($value));
    }

    public function delete(string $key): void
    {
        $this->redis->del([$key]);
    }

    public function increment(string $key, int $ttl): int
    {
        $value = $this->redis->incr($key);
        $this->redis->expire($key, $ttl);

        return $value;
    }
}
