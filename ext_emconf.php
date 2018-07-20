<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Web Service to fetch data',
    'description' => 'Web Service to fetch data in a flexible way. Possible output format: JSON, Atom, HTML. The Web Service is meant for retrieving data only.',
    'category' => 'fe',
    'author' => 'Fabien Udriot',
    'author_email' => 'fabien@ecodev.ch',
    'state' => 'beta',
    'version' => '0.3.0-dev',
    'autoload' => [
        'psr-4' => ['Fab\\WebService\\' => 'Classes']
    ],
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '7.6.0-8.7.99',
                    'vidi' => '0.0.0-0.0.0',
                ],
        ],
];
