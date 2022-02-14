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
    private int $startPage;

    private int $endPage;

    public function __construct(int $startPage, int $endPage)
    {
        Assert::lessThanEq($startPage, $endPage);
        $this->startPage = $startPage;
        $this->endPage = $endPage;
    }

    public function getStartPage(): int
    {
        return $this->startPage;
    }

    public function getEndPage(): int
    {
        return $this->endPage;
    }

    public function range(): array
    {
        return range($this->startPage, $this->endPage);
    }
}
