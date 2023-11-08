<?php

defined('TYPO3') || die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Typo3Pagerfanta',
    'List',
    [
        \Ssch\Typo3Pagerfanta\Controller\TestController::class => 'list',
    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Typo3Pagerfanta',
    'CustomRouteGenerator',
    [
        \Ssch\Typo3Pagerfanta\Controller\TestController::class => 'customRouteGenerator',
    ]
);

