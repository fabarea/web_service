<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Web Service to fetch data',
    'description' => 'Web Service to fetch data in a flexible way. Possible output format: JSON, Atom, HTML. The Web Service is meant for retrieving data only.',
    'category' => 'fe',
    'author' => 'Fabien Udriot',
    'author_email' => 'fabien@ecodev.ch',
    'state' => 'alpha',
    'version' => '0.1.0',
    'autoload' => [
        'psr-4' => ['Fab\\WebService\\' => 'Classes']
    ],
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '7.6.0-7.6.99',
                    'vidi' => '',
                ],
        ],
];
