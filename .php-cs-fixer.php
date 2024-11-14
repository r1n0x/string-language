<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'ordered_class_elements' => true,
        'concat_space' => [
            'spacing' => 'one'
        ],
        'no_extra_blank_lines' => true,
        'phpdoc_align' => [
            'align' => 'left'
        ]
    ])
    ->setFinder($finder)
    ->setIndent("    ")
    ->setLineEnding("\r\n");