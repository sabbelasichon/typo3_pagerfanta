<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\Controller;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\RouteGenerator\RouteGeneratorDecorator;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

final class TestController extends ActionController
{
    /**
     * @phpstan-param positive-int $currentPage
     */
    public function listAction(int $currentPage = 1): ResponseInterface
    {
        $this->assignPagerfantaToView($currentPage);

        return $this->htmlResponse();
    }

    /**
     * @phpstan-param positive-int $currentPage
     */
    public function customRouteGeneratorAction(int $currentPage = 1): ResponseInterface
    {
        $this->assignPagerfantaToView($currentPage);

        $routeGenerator = new RouteGeneratorDecorator(fn (int $page) => (string) $page);
        $this->view->assign('routeGenerator', $routeGenerator);

        return $this->htmlResponse();
    }

    /**
     * @phpstan-param positive-int $currentPage
     */
    private function assignPagerfantaToView(int $currentPage): void
    {
        $adapter = new ArrayAdapter(range('A', 'Z'));

        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage($adapter, $currentPage, 1);
        $this->view->assign('pagerfanta', $pagerfanta);
    }
}
