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
use Pagerfanta\RouteGenerator\PositionRouteGeneratorFactoryInterface;
use Pagerfanta\RouteGenerator\PositionRouteGeneratorInterface;
use Pagerfanta\View\ViewFactoryInterface;
use Ssch\Typo3Pagerfanta\Service\SettingsService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class PaginationViewHelper extends AbstractViewHelper
{
    public function __construct(
        private readonly PositionRouteGeneratorFactoryInterface $routeGeneratorFactory,
        private readonly SettingsService $settingsService,
        private readonly ViewFactoryInterface $viewFactory
    ) {
    }

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
            PositionRouteGeneratorInterface::class,
            'Different RouteGenerator implementation',
        );
    }

    public function render(): string
    {
        $viewName = $this->arguments['viewName'];
        $options = $this->arguments['options'];
        $pagerfanta = $this->arguments['pagerfanta'];
        $routeGenerator = $this->arguments['routeGenerator'] ?? $this->routeGeneratorFactory->createPositionRouteGenerator(
            $options
        );

        $defaultView = $this->settingsService->getStringByPath('default_view');
        $viewName ??= '' !== $defaultView ? $defaultView : 'fluid';

        if (! is_string($viewName)) {
            throw new InvalidArgumentException(sprintf('viewName must be a string. "%s" given', gettype($viewName)));
        }

        return $this->viewFactory->get($viewName)
            ->render($pagerfanta, $routeGenerator, $options);
    }
}
