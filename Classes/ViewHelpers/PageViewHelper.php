<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\ViewHelpers;

use Pagerfanta\RouteGenerator\RouteGeneratorInterface;
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

    public function render(): string
    {
        $page = $this->arguments['page'] ?? null;
        $routeGenerator = $this->arguments['route_generator'] ?? null;

        if (! is_callable($routeGenerator)) {
            throw new \InvalidArgumentException('Route generator must be callable');
        }

        return $routeGenerator((int) $page);
    }
}
