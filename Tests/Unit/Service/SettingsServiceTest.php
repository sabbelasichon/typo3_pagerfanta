<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\Tests\Unit\Service;

use Prophecy\PhpUnit\ProphecyTrait;
use Ssch\Typo3Pagerfanta\Service\SettingsService;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use UnexpectedValueException;

final class SettingsServiceTest extends UnitTestCase
{
    use ProphecyTrait;

    protected SettingsService $settingsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->settingsService = new SettingsService($this->createConfigurationManager());
    }

    public function testGivenPathReturnsCorrectValue(): void
    {
        self::assertEquals('fluid', $this->settingsService->getStringByPath('default_view'));
    }

    public function testGivenPathThrowsExceptionBecauseTheValueIsNotAString(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->settingsService->getStringByPath('foo');
    }

    private function createConfigurationManager(): ConfigurationManagerInterface
    {
        $configurationManager = $this->prophesize(ConfigurationManagerInterface::class);

        $settings = [
            'default_view' => 'fluid',
            'default_fluid_template' => 'Bootstrap.html',
            'foo' => [
                'bar' => 'baz',
            ],
        ];
        $configurationManager->getConfiguration(\Prophecy\Argument::type('string'), 'Typo3Pagerfanta')->willReturn(
            $settings
        );

        return $configurationManager->reveal();
    }
}
