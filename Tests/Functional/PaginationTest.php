<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\Tests\Functional;

use Iterator;
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
     * @var array<int, string>
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
            'setup' => ['EXT:typo3_pagerfanta/Tests/Functional/Fixtures/Frontend/TypoScript/Basic.typoscript'],
        ]);
        $this->setUpSiteConfiguration();
    }

    public function providePaginationForFluidWithDifferentFrameworkTypes(): Iterator
    {
        yield ['Default'];
        yield ['Foundation6'];
        yield ['Tailwind'];
        yield ['TwitterBootstrap'];
        yield ['TwitterBootstrap3'];
        yield ['TwitterBootstrap4'];
        yield ['TwitterBootstrap5'];
    }

    public function providePaginationViewOtherThanFluid(): Iterator
    {
        yield ['default', 'Default'];
        yield ['foundation6', 'Foundation6'];
        yield ['semantic_ui', 'SemanticUi'];
        yield ['twitter_bootstrap', 'TwitterBootstrap'];
        yield ['twitter_bootstrap3', 'TwitterBootstrap3'];
        yield ['twitter_bootstrap4', 'TwitterBootstrap4'];
        yield ['twitter_bootstrap5', 'TwitterBootstrap5'];
    }

    /**
     * @dataProvider providePaginationViewOtherThanFluid
     */
    public function testPaginationWithForDifferentViewsProperly(string $viewType, string $expectedTemplate): void {
        $typoscriptSnippet = <<<CODE_SAMPLE
    plugin.tx_typo3pagerfanta.settings.default_view = ${viewType}
CODE_SAMPLE;

        $this->addTypoScriptToTemplateRecord(self::ROOT_PAGE_UID, $typoscriptSnippet);
        $response = $this->executeFrontendRequest((new InternalRequest())->withPageId(self::ROOT_PAGE_UID));

        $content = $response->getBody()
            ->__toString();

        self::assertStringEqualsFile(
            __DIR__ . '/Fixtures/Expected/Pagerfanta/' . $expectedTemplate . '.html',
            $content
        );
    }

    /**
     * @dataProvider providePaginationForFluidWithDifferentFrameworkTypes
     */
    public function testPaginationWithFluidViewAndWithDifferentFrameworkIntegrationsRendersProperly(
        string $paginationFrameworkType
    ): void {
        $typoscriptSnippet = <<<CODE_SAMPLE
    plugin.tx_typo3pagerfanta.settings.default_view = fluid
    plugin.tx_typo3pagerfanta.settings.default_fluid_template = EXT:typo3_pagerfanta/Resources/Private/Templates/${paginationFrameworkType}.html
CODE_SAMPLE;

        $this->addTypoScriptToTemplateRecord(self::ROOT_PAGE_UID, $typoscriptSnippet);
        $response = $this->executeFrontendRequest((new InternalRequest())->withPageId(self::ROOT_PAGE_UID));

        $content = $response->getBody()
            ->__toString();

        self::assertStringEqualsFile(
            __DIR__ . '/Fixtures/Expected/Fluid/' . $paginationFrameworkType . '.html',
            $content
        );
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
