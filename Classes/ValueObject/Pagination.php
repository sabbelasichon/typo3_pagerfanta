<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\ValueObject;

use Pagerfanta\PagerfantaInterface;

final class Pagination
{
    private int $currentPage;

    private int $nbPages;

    private PagerfantaInterface $pagerfanta;

    private PageRange $pageRange;

    private bool $showPages;

    /**
     * @phpstan-param int<0, max> $currentPage
     * @phpstan-param int<0, max> $nbPages
     */
    public function __construct(PagerfantaInterface $pagerfanta, PageRange $pageRange, int $currentPage, int $nbPages, bool $showPages)
    {
        $this->currentPage = $currentPage;
        $this->nbPages = $nbPages;
        $this->pagerfanta = $pagerfanta;
        $this->pageRange = $pageRange;
        $this->showPages = $showPages;
    }

    public function getHasPreviousPage(): bool
    {
        return $this->pagerfanta->hasPreviousPage();
    }

    public function getPreviousPage(): int
    {
        return $this->pagerfanta->getPreviousPage();
    }

    public function getHasNextPage(): bool
    {
        return $this->pagerfanta->hasNextPage();
    }

    public function getNextPage(): int
    {
        return $this->pagerfanta->getNextPage();
    }

    public function getStartPage(): int
    {
        return $this->pageRange->getStartPage();
    }

    public function getEndPage(): int
    {
        return $this->pageRange->getEndPage();
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getNbPages(): int
    {
        return $this->nbPages;
    }

    public function getPages(): array
    {
        return $this->pageRange->range();
    }

    public function isShowPages(): bool
    {
        return $this->showPages;
    }
}
