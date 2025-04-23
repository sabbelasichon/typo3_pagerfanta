<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\Adapter;

use Pagerfanta\Adapter\AdapterInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use Webmozart\Assert\Assert;

final readonly class QueryResultAdapter implements AdapterInterface
{
    public function __construct(
        private QueryResultInterface $queryResult
    ) {
    }

    public function getNbResults(): int
    {
        return count($this->queryResult);
    }

    public function getSlice(int $offset, int $length): iterable
    {
        Assert::natural($offset);
        Assert::natural($length);

        return $this->queryResult
            ->getQuery()
            ->setLimit($length)
            ->setOffset($offset)
            ->execute();
    }
}
