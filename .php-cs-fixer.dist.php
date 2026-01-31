<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['vendor', 'var', 'coverage']);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        '@PHP82Migration' => true,
        'declare_strict_types' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);
