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
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class PaginationTest extends FunctionalTestCase
{
    /**
     * @var int
     */
    private const ROOT_PAGE_UID = 1;

    /**
     * @var string[]
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/typo3_pagerfanta'];

    /**
     * @var string[]
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
        $this->setUpFrontendRootPage(self::ROOT_PAGE_UID, [
            'setup' => ['EXT:typo3_pagerfanta/Tests/Functional/Fixtures/Frontend/Basic.typoscript'],
        ]);
        $this->setUpSites(self::ROOT_PAGE_UID, []);
    }

    public function testPaginationIsProperlyCreated(): void
    {
        $response = $this->executeFrontendRequest((new InternalRequest())->withPageId(self::ROOT_PAGE_UID));

        $content = $response->getBody()
            ->__toString();

        self::assertStringEqualsFile(__DIR__ . '/Fixtures/Expected/PaginationFoundation6.html', $content);
    }

    protected function setUpSites(int $pageId, array $sites): void
    {
        if (! isset($sites[$pageId])) {
            $sites[$pageId] = 'EXT:typo3_pagerfanta/Tests/Functional/Fixtures/Frontend/site.yaml';
        }

        foreach ($sites as $identifier => $file) {
            $path = Environment::getConfigPath() . '/sites/' . $identifier . '/';
            $target = $path . 'config.yaml';
            if (! file_exists($target)) {
                GeneralUtility::mkdir_deep($path);
                if (! file_exists($file)) {
                    $file = GeneralUtility::getFileAbsFileName($file);
                }
                $fileContent = file_get_contents($file);
                $fileContent = str_replace('\'{rootPageId}\'', (string) $pageId, (string) $fileContent);
                GeneralUtility::writeFile($target, $fileContent);
            }
        }
    }
}
