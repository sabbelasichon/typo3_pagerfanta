<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Pagerfanta\RouteGenerator\RouteGeneratorFactoryInterface;
use Pagerfanta\View\DefaultView;
use Pagerfanta\View\Foundation6View;
use Pagerfanta\View\SemanticUiView;
use Pagerfanta\View\TwitterBootstrap3View;
use Pagerfanta\View\TwitterBootstrap4View;
use Pagerfanta\View\TwitterBootstrap5View;
use Pagerfanta\View\TwitterBootstrapView;
use Pagerfanta\View\ViewFactory;
use Pagerfanta\View\ViewFactoryInterface;
use Ssch\Typo3Pagerfanta\DependencyInjection\CompilerPass\RegisterPagerfantaViewsPass;
use Ssch\Typo3Pagerfanta\RouteGenerator\RequestAwareRouteGeneratorFactory;
use Ssch\Typo3Pagerfanta\Service\SettingsService;
use Ssch\Typo3Pagerfanta\View\FluidView;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Web\RequestBuilder;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Fluid\View\StandaloneView;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void {
    $services = $containerConfigurator->services();

    // Pagerfanta views
    $services->set('pagerfanta.view.default', DefaultView::class)->tag('pagerfanta.view', [
        'alias' => 'default',
    ]);
    $services->set('pagerfanta.view.foundation6', Foundation6View::class)->tag('pagerfanta.view', [
        'alias' => 'foundation6',
    ]);
    $services->set('pagerfanta.view.semantic_ui', SemanticUiView::class)->tag('pagerfanta.view', [
        'alias' => 'semantic_ui',
    ]);
    $services->set('pagerfanta.view.twitter_bootstrap', TwitterBootstrapView::class)->tag('pagerfanta.view', [
        'alias' => 'twitter_bootstrap',
    ]);
    $services->set('pagerfanta.view.twitter_bootstrap3', TwitterBootstrap3View::class)->tag('pagerfanta.view', [
        'alias' => 'twitter_bootstrap3',
    ]);
    $services->set('pagerfanta.view.twitter_bootstrap4', TwitterBootstrap4View::class)->tag('pagerfanta.view', [
        'alias' => 'twitter_bootstrap4',
    ]);
    $services->set('pagerfanta.view.twitter_bootstrap5', TwitterBootstrap5View::class)->tag('pagerfanta.view', [
        'alias' => 'twitter_bootstrap5',
    ]);

    $services->set('pagerfanta.view_factory', ViewFactory::class)->public();
    $services->alias(ViewFactoryInterface::class, 'pagerfanta.view_factory')->public();

    $services->set(SettingsService::class)->args([service(ConfigurationManagerInterface::class)])->public();

    $services->set('pagerfanta.view.fluid', FluidView::class)
        ->args([service(StandaloneView::class), service(SettingsService::class)])
        ->tag('pagerfanta.view', [
            'alias' => 'fluid',
        ]);

    $services->set('pagerfanta.route_generator_factory', RequestAwareRouteGeneratorFactory::class)
        ->args([service(UriBuilder::class), service(RequestBuilder::class)])
        ->public();

    $services->alias(RouteGeneratorFactoryInterface::class, 'pagerfanta.route_generator_factory')
        ->public();

    $containerBuilder->addCompilerPass(new RegisterPagerfantaViewsPass());
};
