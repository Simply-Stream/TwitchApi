<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('tests');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'declare_strict_types' => true,
        'no_superfluous_phpdoc_tags' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
