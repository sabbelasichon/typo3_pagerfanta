<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Pagination based on pagerfanta library',
    'description' => 'Pagination based on pagerfanta library for TYPO3',
    'category' => 'fe',
    'author' => 'Sebastian Schreiber',
    'author_email' => 'breakpoint@schreibersebastian.de',
    'state' => 'stable',
    'version' => '0.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.2-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
