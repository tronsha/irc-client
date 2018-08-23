<?php

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([
                __DIR__ . DIRECTORY_SEPARATOR . 'src',
                __DIR__ . DIRECTORY_SEPARATOR . 'tests',
            ])
            ->name('*.php')
    )
;