<?php

$finder = PhpCsFixer\Finder::create()
                           ->in([
                               __DIR__ . '/src',
                               __DIR__ . '/tests',
                           ])
                           ->name('*.php')
                           ->notName('*.blade.php')
                           ->ignoreDotFiles(true)
                           ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12'                            => true,
        'array_syntax'                      => ['syntax' => 'short'],
        'ordered_imports'                   => ['sort_algorithm' => 'length'],
        'no_extra_blank_lines'              => ['tokens' => ['use']],
        'ordered_traits'                    => true,
        'no_unused_imports'                 => true,
        'not_operator_with_successor_space' => true,
        'trailing_comma_in_multiline'       => true,
        'phpdoc_scalar'                     => true,
        'unary_operator_spaces'             => true,
        'binary_operator_spaces'            => [
            'default'   => 'single_space',
            'operators' => [
                '=>' => 'align_single_space',
            ],
        ],
        'blank_line_before_statement'       => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'phpdoc_single_line_var_spacing'    => true,
        'phpdoc_var_without_name'           => true,
        'method_argument_space'             => [
            'on_multiline'                     => 'ensure_fully_multiline',
            'keep_multiple_spaces_after_comma' => true,
        ],
        'single_trait_insert_per_statement' => true,
    ])
    ->setFinder($finder);
