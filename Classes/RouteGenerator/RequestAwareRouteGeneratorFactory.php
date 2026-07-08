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
use Psr\Http\Message\ServerRequestInterface;
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

        /** @var ServerRequestInterface $serverRequest */
        $serverRequest = $GLOBALS['TYPO3_REQUEST'];

        $request = $this->requestBuilder->build($serverRequest);
        $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObjectRenderer->setRequest($serverRequest);
        $request = $request->withAttribute('currentContentObject', $contentObjectRenderer);

        /** @var ExtbaseRequestParameters $extbaseRequestParameters */
        $extbaseRequestParameters = $request->getAttribute('extbase');

        if (null === $options['actionName']) {
            $options['actionName'] = $extbaseRequestParameters->getControllerActionName();
        }

        return new RequestAwareRouteGenerator($this->uriBuilder, $request, $options);
    }
}
