<?php

$config = new PhpCsFixer\Config();
return $config
    ->setUsingCache(true)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'assign_null_coalescing_to_coalesce_equal' => true,
        'binary_operator_spaces' => true,
        'blank_line_after_namespace' => true,
        'cast_spaces' => true,
        'concat_space' => ['spacing' => 'one'],
        'include' => true,
        'list_syntax' => true,
        'lowercase_cast' => true,
        'lowercase_static_reference' => true,
        'method_argument_space' => ['on_multiline' => 'ignore'],
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_break_comment' => false,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => true,
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_unused_imports' => true,
        'no_whitespace_in_blank_line' => true,
        'object_operator_without_whitespace' => true,
        'phpdoc_indent' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_scalar' => true,
        'phpdoc_to_comment' => true,
        'phpdoc_trim' => true,
        'short_scalar_cast' => true,
        'ternary_operator_spaces' => true,
        'ternary_to_null_coalescing' => true,
        'trailing_comma_in_multiline' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('application/asset')
            ->exclude('application/data/doctrine-proxies')
            ->exclude('application/data/media-types')
            ->exclude('application/data/overrides')
            ->exclude('config')
            ->exclude('files')
            ->exclude('modules')
            ->exclude('node_modules')
            ->exclude('docker')
            ->exclude('vendor')
            ->in(__DIR__)
    )
;
