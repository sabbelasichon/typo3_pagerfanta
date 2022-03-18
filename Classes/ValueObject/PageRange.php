<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\ValueObject;

use Webmozart\Assert\Assert;

final class PageRange
{
    /**
     * @phpstan-var int<0, max>
     */
    private int $startPage;

    /**
     * @phpstan-var int<0, max>
     */
    private int $endPage;

    /**
     * @phpstan-param int<0, max> $startPage
     * @phpstan-param int<0, max> $endPage
     */
    public function __construct(int $startPage, int $endPage)
    {
        Assert::natural($startPage);
        Assert::natural($endPage);
        Assert::lessThanEq($startPage, $endPage);
        $this->startPage = $startPage;
        $this->endPage = $endPage;
    }

    /**
     * @phpstan-return int<0, max>
     */
    public function getStartPage(): int
    {
        return $this->startPage;
    }

    /**
     * @phpstan-return int<0, max>
     */
    public function getEndPage(): int
    {
        return $this->endPage;
    }

    /**
     * @return int[]
     */
    public function range(): array
    {
        return range($this->startPage, $this->endPage);
    }
}
