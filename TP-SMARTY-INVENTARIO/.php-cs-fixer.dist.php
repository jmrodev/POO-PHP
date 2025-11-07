<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('templates_c')
    ->exclude('vendor')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'trailing_comma_in_multiline' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'binary_operator_spaces' => ['default' => 'single_space'],
        'concat_space' => ['spacing' => 'one'],
    ])
    ->setFinder($finder)
;
