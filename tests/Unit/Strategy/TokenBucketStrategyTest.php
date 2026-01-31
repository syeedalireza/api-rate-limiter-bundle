<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Tests\Unit\Strategy;

use PHPUnit\Framework\TestCase;
use Syeedalireza\RateLimiterBundle\Storage\StorageInterface;
use Syeedalireza\RateLimiterBundle\Strategy\TokenBucketStrategy;

final class TokenBucketStrategyTest extends TestCase
{
    public function testFirstRequestIsAllowed(): void
    {
        $storage = $this->createMock(StorageInterface::class);
        $storage->method('get')->willReturn(null);
        
        $strategy = new TokenBucketStrategy($storage);
        $status = $strategy->check('test', 100, 3600);

        $this->assertTrue($status->isAllowed());
        $this->assertEquals(99, $status->remaining);
    }
}
