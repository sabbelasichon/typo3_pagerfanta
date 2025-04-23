<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\ViewHelpers;

use InvalidArgumentException;
use Pagerfanta\PagerfantaInterface;
use Pagerfanta\RouteGenerator\RouteGeneratorFactoryInterface;
use Pagerfanta\RouteGenerator\RouteGeneratorInterface;
use Pagerfanta\View\ViewFactoryInterface;
use Ssch\Typo3Pagerfanta\Service\SettingsService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class PaginationViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('pagerfanta', PagerfantaInterface::class, 'The pagerfanta', true);
        $this->registerArgument('viewName', 'string', 'The view', false);
        $this->registerArgument('options', 'array', 'The options', false, []);
        $this->registerArgument(
            'routeGenerator',
            RouteGeneratorInterface::class,
            'Different RouteGenerator implementation',
            false
        );
    }

    public function render(): string
    {
        $viewName = $this->arguments['viewName'];
        $options = $this->arguments['options'];
        $pagerfanta = $this->arguments['pagerfanta'];
        $routeGenerator = $this->arguments['routeGenerator'] ?? self::createRouteGenerator($options);

        $viewFactory = GeneralUtility::makeInstance(ViewFactoryInterface::class);

        $settingsService = GeneralUtility::makeInstance(SettingsService::class);

        $viewName ??= $settingsService->getStringByPath('default_view') ?? 'fluid';

        if (! is_string($viewName)) {
            throw new InvalidArgumentException(sprintf('viewName must be a string. "%s" given', gettype($viewName)));
        }

        return $viewFactory->get($viewName)
            ->render($pagerfanta, $routeGenerator, $options);
    }

    private static function createRouteGenerator(array $options = []): RouteGeneratorInterface
    {
        $routeGeneratorFactory = GeneralUtility::makeInstance(RouteGeneratorFactoryInterface::class);

        return $routeGeneratorFactory->create($options);
    }
}
