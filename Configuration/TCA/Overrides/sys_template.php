<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'typo3_pagerfanta',
    'Configuration/TypoScript',
    'TYPO3 Pagerfanta'
);
