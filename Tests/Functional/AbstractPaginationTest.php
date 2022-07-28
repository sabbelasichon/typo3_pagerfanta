<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\Tests\Functional;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

abstract class AbstractPaginationTest extends FunctionalTestCase
{
    /**
     * @var int
     */
    protected const ROOT_PAGE_UID = 1;

    /**
     * @var non-empty-string[]
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/typo3_pagerfanta'];

    /**
     * @var non-empty-string[]
     */
    protected $coreExtensionsToLoad = ['extbase', 'fluid'];

    protected $configurationToUseInTestInstance = [
        'SYS' => [
            'encryptionKey' => '42',
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importDataSet(__DIR__ . '/Fixtures/Database/pages.xml');
        $this->setUpSiteConfiguration();
    }

    private function setUpSiteConfiguration(): void
    {
        $sites = [
            self::ROOT_PAGE_UID => 'EXT:typo3_pagerfanta/Tests/Functional/Fixtures/Frontend/config/site.yaml',
        ];

        foreach ($sites as $identifier => $file) {
            $path = Environment::getConfigPath() . '/sites/' . $identifier . '/';
            $target = $path . 'config.yaml';
            if (! file_exists($target)) {
                GeneralUtility::mkdir_deep($path);
                if (! file_exists($file)) {
                    $file = GeneralUtility::getFileAbsFileName($file);
                }
                $fileContent = file_get_contents($file);
                $fileContent = str_replace('\'{rootPageId}\'', (string) self::ROOT_PAGE_UID, (string) $fileContent);
                GeneralUtility::writeFile($target, $fileContent);
            }
        }
    }
}
