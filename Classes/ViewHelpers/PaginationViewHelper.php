<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\ViewHelpers;

use Closure;
use InvalidArgumentException;
use Pagerfanta\PagerfantaInterface;
use Pagerfanta\RouteGenerator\RouteGeneratorFactoryInterface;
use Pagerfanta\RouteGenerator\RouteGeneratorInterface;
use Pagerfanta\View\ViewFactoryInterface;
use Ssch\Typo3Pagerfanta\Service\SettingsService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class PaginationViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('pagerfanta', PagerfantaInterface::class, 'The pagerfanta', true);
        $this->registerArgument('viewName', 'string', 'The view', false);
        $this->registerArgument('options', 'array', 'The options', false, []);
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $viewName = $arguments['viewName'];
        $options = $arguments['options'];
        $pagerfanta = $arguments['pagerfanta'];

        /** @var ViewFactoryInterface $viewFactory */
        $viewFactory = GeneralUtility::getContainer()->get(ViewFactoryInterface::class);

        /** @var SettingsService $settingsService */
        $settingsService = GeneralUtility::getContainer()->get(SettingsService::class);

        $viewName ??= $settingsService->getByPath('default_view') ?? 'fluid';

        if (! is_string($viewName)) {
            throw new InvalidArgumentException(sprintf('viewName must be a string. "%s" given', gettype($viewName)));
        }

        return $viewFactory->get($viewName)
            ->render($pagerfanta, self::createRouteGenerator($options), $options);
    }

    private static function createRouteGenerator(array $options = []): RouteGeneratorInterface
    {
        /** @var RouteGeneratorFactoryInterface $routeGeneratorFactory */
        $routeGeneratorFactory = GeneralUtility::getContainer()->get(RouteGeneratorFactoryInterface::class);

        return $routeGeneratorFactory->create($options);
    }
}
