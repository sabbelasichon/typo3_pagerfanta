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

final class QueryResultAdapter implements AdapterInterface
{
    private QueryResultInterface $queryResult;

    public function __construct(QueryResultInterface $queryResult)
    {
        $this->queryResult = $queryResult;
    }

    public function getNbResults(): int
    {
        return count($this->queryResult);
    }

    public function getSlice(int $offset, int $length): iterable
    {
        return $this->queryResult
            ->getQuery()
            ->setLimit($length)
            ->setOffset($offset)
            ->execute();
    }
}
