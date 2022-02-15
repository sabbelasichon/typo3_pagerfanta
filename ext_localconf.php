<?php

defined('TYPO3_MODE') || die('Access denied.');

$boot = static function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Typo3Pagerfanta',
        'List',
        [
            \Ssch\Typo3Pagerfanta\Controller\TestController::class => 'list2',
        ]
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Typo3Pagerfanta',
        'CustomRouteGenerator',
        [
            \Ssch\Typo3Pagerfanta\Controller\TestController::class => 'customRouteGenerator',
        ]
    );
};

$boot();
unset($boot);
