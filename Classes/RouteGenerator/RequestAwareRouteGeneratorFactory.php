<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\RouteGenerator;

use Pagerfanta\RouteGenerator\RouteGeneratorFactoryInterface;
use Pagerfanta\RouteGenerator\RouteGeneratorInterface;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Web\RequestBuilder;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

final class RequestAwareRouteGeneratorFactory implements RouteGeneratorFactoryInterface
{
    private RequestBuilder $requestBuilder;

    private UriBuilder $uriBuilder;

    public function __construct(UriBuilder $uriBuilder, RequestBuilder $requestBuilder)
    {
        $this->requestBuilder = $requestBuilder;
        $this->uriBuilder = $uriBuilder;
    }

    public function create(array $options = []): RouteGeneratorInterface
    {
        $options = array_replace(
            [
                'actionName' => null,
                'pageParameter' => 'currentPage',
                'addQueryString' => true,
                'argumentsToBeExcludedFromQueryString' => [],
            ],
            $options
        );

        $request = $this->requestBuilder->build($GLOBALS['TYPO3_REQUEST']);
        /** @var ExtbaseRequestParameters $extbaseRequestParameters */
        $extbaseRequestParameters = $request->getAttribute('extbase');

        if (null === $options['actionName']) {
            $options['actionName'] = $extbaseRequestParameters->getControllerActionName();
        }

        return new RequestAwareRouteGenerator($this->uriBuilder, $request, $options);
    }
}
