<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('rate_limiter');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('default_algorithm')->defaultValue('token_bucket')->end()
                ->arrayNode('redis')
                    ->children()
                        ->scalarNode('client')->defaultValue('redis://localhost:6379')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
