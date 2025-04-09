<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = Finder::create()
    ->in('src')
    ->in('example')
    ->in('tests')
    ->exclude('vendor');

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRiskyAllowed(true) // risky rules are allowed
    ->setRules([
        '@PER-CS2.0' => true,
        '@PER-CS2.0:risky' => true,
    ])
    ->setFinder($finder);