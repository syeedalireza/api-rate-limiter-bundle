<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Syeedalireza\RateLimiterBundle\Service\RateLimiter;
use Syeedalireza\RateLimiterBundle\Strategy\RateLimitStrategyInterface;
use Syeedalireza\RateLimiterBundle\ValueObject\RateLimitStatus;

final class RateLimiterTest extends TestCase
{
    public function testCheck(): void
    {
        $strategy = $this->createMock(RateLimitStrategyInterface::class);
        $expectedStatus = new RateLimitStatus(true, 100, 99, time() + 3600);
        
        $strategy->expects($this->once())
            ->method('check')
            ->with('key', 100, 3600, 1)
            ->willReturn($expectedStatus);

        $rateLimiter = new RateLimiter($strategy);
        $status = $rateLimiter->check('key', 100, 3600);

        $this->assertTrue($status->isAllowed());
    }

    public function testReset(): void
    {
        $strategy = $this->createMock(RateLimitStrategyInterface::class);
        $strategy->expects($this->once())->method('reset')->with('key');

        $rateLimiter = new RateLimiter($strategy);
        $rateLimiter->reset('key');

        $this->assertTrue(true);
    }
}
