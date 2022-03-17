<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\View;

use Pagerfanta\PagerfantaInterface;
use Pagerfanta\RouteGenerator\RouteGeneratorDecorator;
use Pagerfanta\View\View;
use Ssch\Typo3Pagerfanta\Service\SettingsService;
use Ssch\Typo3Pagerfanta\ValueObject\PageRange;
use Ssch\Typo3Pagerfanta\ValueObject\Pagination;
use TYPO3\CMS\Fluid\View\StandaloneView;

final class FluidView extends View
{
    public const DEFAULT_TEMPLATE = 'EXT:typo3_pagerfanta/Resources/Private/Templates/Default.html';

    private string $template = self::DEFAULT_TEMPLATE;

    private StandaloneView $standaloneView;

    private string $defaultTemplate;

    private bool $showPages = false;

    public function __construct(StandaloneView $standaloneView, SettingsService $settingsService)
    {
        $this->standaloneView = $standaloneView;
        $this->standaloneView->getTemplatePaths()
            ->fillDefaultsByPackageName('typo3_pagerfanta');

        $this->defaultTemplate = $settingsService->getStringByPath('default_fluid_template');
    }

    public function render(PagerfantaInterface $pagerfanta, callable $routeGenerator, array $options = []): string
    {
        $options = array_replace([
                'showPages' => true,
            ], $options);

        $this->initializePagerfanta($pagerfanta);
        $this->initializeOptions($options);
        $this->calculateStartAndEndPage();

        $this->standaloneView->setTemplatePathAndFilename($this->template);

        $this->standaloneView->assignMultiple([
            'pagination' => new Pagination(
                $pagerfanta,
                new PageRange((int) $this->startPage, (int) $this->endPage),
                (int) $this->currentPage,
                (int) $this->nbPages,
                $this->showPages
            ),
            'route_generator' => $this->decorateRouteGenerator($routeGenerator),
            'options' => $options,
        ]);

        return $this->standaloneView->render();
    }

    public function getName(): string
    {
        return 'fluid';
    }

    protected function initializeOptions(array $options): void
    {
        if (isset($options['template'])) {
            $this->defaultTemplate = $options['template'];
        } elseif ('' !== $this->defaultTemplate) {
            $this->template = $this->defaultTemplate;
        }

        if (isset($options['showPages'])) {
            $this->showPages = (bool) $options['showPages'];
        }

        parent::initializeOptions($options);
    }

    private function decorateRouteGenerator(callable $routeGenerator): RouteGeneratorDecorator
    {
        return new RouteGeneratorDecorator($routeGenerator);
    }
}
