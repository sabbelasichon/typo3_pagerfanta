<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\RouteGenerator;

use Pagerfanta\RouteGenerator\RouteGeneratorInterface;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

final class RequestAwareRouteGenerator implements RouteGeneratorInterface
{
    public function __construct(
        private readonly UriBuilder $uriBuilder,
        private readonly Request $request,
        private array $options = []
    ) {
    }

    public function __invoke(int $page): string
    {
        $this->uriBuilder->reset();
        $this->uriBuilder->setRequest($this->request);
        $this->uriBuilder->setAddQueryString((bool) $this->options['addQueryString']);
        $this->uriBuilder->setArgumentsToBeExcludedFromQueryString(
            $this->options['argumentsToBeExcludedFromQueryString']
        );

        if (isset($this->options['argumentPrefix'])) {
            $this->uriBuilder->setArgumentPrefix($this->options['argumentPrefix']);
        }

        return $this->uriBuilder->uriFor($this->options['actionName'], [
            $this->options['pageParameter'] => $page,
        ]);
    }
}
