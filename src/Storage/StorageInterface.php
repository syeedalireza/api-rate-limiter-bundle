<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Storage;

interface StorageInterface
{
    public function get(string $key): ?array;

    public function set(string $key, array $value, int $ttl): void;

    public function delete(string $key): void;

    public function increment(string $key, int $ttl): int;
}
