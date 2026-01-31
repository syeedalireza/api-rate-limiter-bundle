<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Syeedalireza\RateLimiterBundle\DependencyInjection\Configuration;

final class ConfigurationTest extends TestCase
{
    public function testDefaultConfiguration(): void
    {
        $processor = new Processor();
        $configuration = new Configuration();
        
        $config = $processor->processConfiguration($configuration, []);

        $this->assertEquals('token_bucket', $config['default_algorithm']);
        $this->assertEquals('redis://localhost:6379', $config['redis']['client']);
    }

    public function testCustomConfiguration(): void
    {
        $processor = new Processor();
        $configuration = new Configuration();
        
        $config = $processor->processConfiguration($configuration, [[
            'default_algorithm' => 'sliding_window',
            'redis' => ['client' => 'redis://custom:6379'],
        ]]);

        $this->assertEquals('sliding_window', $config['default_algorithm']);
        $this->assertEquals('redis://custom:6379', $config['redis']['client']);
    }
}
