<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\Service;

use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use UnexpectedValueException;

final class SettingsService
{
    private ?array $settings = null;

    private ConfigurationManagerInterface $configurationManager;

    public function __construct(ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    public function getSettings(): array
    {
        if (null === $this->settings) {
            $this->settings = $this->configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
                'Typo3Pagerfanta',
            );
        }
        return $this->settings;
    }

    public function getStringByPath(string $path): string
    {
        $value = $this->getByPath($path);

        if (null === $value) {
            return '';
        }

        if (! is_string($value)) {
            $message = sprintf('Cannot cast "%s" to string', $path);
            throw new UnexpectedValueException($message);
        }

        return $value;
    }

    /**
     * @return mixed
     */
    private function getByPath(string $path)
    {
        return ObjectAccess::getPropertyPath($this->getSettings(), $path);
    }
}
