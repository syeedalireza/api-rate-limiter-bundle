<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Tests\Unit\Strategy;

use PHPUnit\Framework\TestCase;
use Syeedalireza\RateLimiterBundle\Storage\StorageInterface;
use Syeedalireza\RateLimiterBundle\Strategy\SlidingWindowStrategy;

final class SlidingWindowStrategyTest extends TestCase
{
    public function testFirstRequestIsAllowed(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $storage->method('get')->willReturn(null);
        
        $strategy = new SlidingWindowStrategy($storage);
        $status = $strategy->check('test', 10, 60);

        $this->assertTrue($status->isAllowed());
        $this->assertEquals(9, $status->remaining);
    }

    public function testRespectsRateLimit(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $storage->method('get')->willReturn(['requests' => array_fill(0, 10, time())]);
        
        $strategy = new SlidingWindowStrategy($storage);
        $status = $strategy->check('test', 10, 60);

        $this->assertFalse($status->isAllowed());
        $this->assertEquals(0, $status->remaining);
    }
}
