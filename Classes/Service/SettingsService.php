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

    public function __construct(
        private readonly ConfigurationManagerInterface $configurationManager
    ) {
    }

    public function getSettings(): array
    {
        if ($this->settings === null) {
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

        if ($value === null) {
            return '';
        }

        if (! is_string($value)) {
            $message = sprintf('Cannot cast "%s" to string', $path);
            throw new UnexpectedValueException($message);
        }

        return $value;
    }

    private function getByPath(string $path): mixed
    {
        return ObjectAccess::getPropertyPath($this->getSettings(), $path);
    }
}
