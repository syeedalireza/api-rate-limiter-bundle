<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Tests\Unit\Strategy;

use PHPUnit\Framework\TestCase;
use Syeedalireza\RateLimiterBundle\Storage\StorageInterface;
use Syeedalireza\RateLimiterBundle\Strategy\FixedWindowStrategy;

final class FixedWindowStrategyTest extends TestCase
{
    public function testAllowsRequestWithinLimit(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $storage->method('get')->willReturn(['count' => 5]);
        
        $strategy = new FixedWindowStrategy($storage);
        $status = $strategy->check('test', 10, 60);

        $this->assertTrue($status->isAllowed());
    }

    public function testDeniesRequestOverLimit(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $storage->method('get')->willReturn(['count' => 10]);
        
        $strategy = new FixedWindowStrategy($storage);
        $status = $strategy->check('test', 10, 60);

        $this->assertFalse($status->isAllowed());
    }
}
