<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Tests\Unit\Storage;

use PHPUnit\Framework\TestCase;
use Predis\ClientInterface;
use Syeedalireza\RateLimiterBundle\Storage\RedisStorage;

final class RedisStorageTest extends TestCase
{
    public function testSetAndGet(): void
    {
        $redis = $this->createMock(ClientInterface::class);
        $redis->expects($this->once())
            ->method('setex')
            ->with('test', 3600, json_encode(['count' => 1]));

        $storage = new RedisStorage($redis);
        $storage->set('test', ['count' => 1], 3600);

        $this->assertTrue(true);
    }

    public function testDelete(): void
    {
        $redis = $this->createMock(ClientInterface::class);
        $redis->expects($this->once())
            ->method('del')
            ->with(['test']);

        $storage = new RedisStorage($redis);
        $storage->delete('test');

        $this->assertTrue(true);
    }
}
