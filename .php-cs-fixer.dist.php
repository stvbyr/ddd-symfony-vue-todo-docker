<?php

declare(strict_types=1);

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PHP80Migration:risky' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude(['var', 'node_modules', '.git'])
            ->notPath('*.cache')
            ->in(__DIR__)
    );
