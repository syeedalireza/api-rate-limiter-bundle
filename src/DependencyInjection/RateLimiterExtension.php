<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class RateLimiterExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        // Register services based on configuration
        $container->setParameter('rate_limiter.default_algorithm', $config['default_algorithm']);
        $container->setParameter('rate_limiter.redis_dsn', $config['redis']['client']);
    }
    
    public function getAlias(): string
    {
        return 'rate_limiter';
    }
}
