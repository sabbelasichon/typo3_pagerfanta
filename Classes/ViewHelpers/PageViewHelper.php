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
use Pagerfanta\RouteGenerator\RouteGeneratorInterface;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class PageViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument(
            'route_generator',
            RouteGeneratorInterface::class,
            'The pagerfanta route generator',
            true
        );
        $this->registerArgument('page', 'int', 'The page', true);
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $page = (int) $arguments['page'];
        $routeGenerator = $arguments['route_generator'];

        return $routeGenerator($page);
    }
}
