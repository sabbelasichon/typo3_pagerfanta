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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Web\RequestBuilder;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

final readonly class RequestAwareRouteGeneratorFactory implements RouteGeneratorFactoryInterface
{
    public function __construct(
        private UriBuilder $uriBuilder,
        private RequestBuilder $requestBuilder
    ) {
    }

    public function create(array $options = []): RouteGeneratorInterface
    {
        $options = array_replace(
            [
                'actionName' => null,
                'pageParameter' => 'currentPage',
                'addQueryString' => true,
                'argumentsToBeExcludedFromQueryString' => [],
                'omitFirstPage' => false,
            ],
            $options
        );

        $request = $this->requestBuilder->build($GLOBALS['TYPO3_REQUEST']);
        $request = $request->withAttribute(
            'currentContentObject',
            GeneralUtility::makeInstance(ContentObjectRenderer::class)
        );

        /** @var ExtbaseRequestParameters $extbaseRequestParameters */
        $extbaseRequestParameters = $request->getAttribute('extbase');

        if ($options['actionName'] === null) {
            $options['actionName'] = $extbaseRequestParameters->getControllerActionName();
        }

        return new RequestAwareRouteGenerator($this->uriBuilder, $request, $options);
    }
}
