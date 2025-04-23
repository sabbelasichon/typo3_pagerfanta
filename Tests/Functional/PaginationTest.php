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
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;

final class PaginationTest extends AbstractPaginationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFrontendRootPage(self::ROOT_PAGE_UID, [
            'setup' => ['EXT:typo3_pagerfanta/Tests/Functional/Fixtures/Frontend/TypoScript/Basic.typoscript'],
        ]);
    }

    public static function providePaginationForFluidWithDifferentFrameworkTypes(): Iterator
    {
        yield 'Default' => ['Default'];
        yield 'Foundation6' => ['Foundation6'];
        yield 'Tailwind' => ['Tailwind'];
        yield 'TwitterBootstrap' => ['TwitterBootstrap'];
        yield 'TwitterBootstrap3' => ['TwitterBootstrap3'];
        yield 'TwitterBootstrap4' => ['TwitterBootstrap4'];
        yield 'TwitterBootstrap5' => ['TwitterBootstrap5'];
        yield 'MaterializeCss' => ['MaterializeCss'];
        yield 'Bulma' => ['Bulma'];
    }

    public static function providePaginationViewOtherThanFluid(): Iterator
    {
        yield 'Default' => ['default'];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providePaginationViewOtherThanFluid')]
    public function testPaginationWithForDifferentViewsProperly(string $viewType): void
    {
        $typoscriptSnippet = <<<CODE_SAMPLE
    plugin.tx_typo3pagerfanta.settings.default_view = {$viewType}
CODE_SAMPLE;

        $this->addTypoScriptToTemplateRecord(self::ROOT_PAGE_UID, $typoscriptSnippet);
        $response = $this->executeFrontendSubRequest((new InternalRequest())->withPageId(self::ROOT_PAGE_UID));

        $content = $response->getBody()
            ->__toString();

        self::assertStringContainsString('href="/p/2"', $content);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providePaginationForFluidWithDifferentFrameworkTypes')]
    public function testPaginationWithFluidViewAndWithDifferentFrameworkIntegrationsRendersProperly(
        string $paginationFrameworkType
    ): void {
        $typoscriptSnippet = <<<CODE_SAMPLE
    plugin.tx_typo3pagerfanta.settings.default_view = fluid
    plugin.tx_typo3pagerfanta.settings.default_fluid_template = EXT:typo3_pagerfanta/Resources/Private/Templates/{$paginationFrameworkType}.html
CODE_SAMPLE;

        $this->addTypoScriptToTemplateRecord(self::ROOT_PAGE_UID, $typoscriptSnippet);
        $response = $this->executeFrontendSubRequest((new InternalRequest())->withPageId(self::ROOT_PAGE_UID));

        $content = $response->getBody()
            ->__toString();

        self::assertStringContainsString('href="/p/2"', $content);
        self::assertStringContainsString('href="/p/3"', $content);
        self::assertStringContainsString('href="/p/4"', $content);
        self::assertStringContainsString('href="/p/5"', $content);
    }
}
