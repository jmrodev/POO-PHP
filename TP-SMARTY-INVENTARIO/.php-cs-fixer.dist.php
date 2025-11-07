<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('templates_c')
    ->exclude('vendor')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
    ])
    ->setFinder($finder)
;
