<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\Adapter;

use Doctrine\DBAL\Query\QueryBuilder as DoctrineQueryBuilder;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Exception\InvalidArgumentException;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

/**
 * Adapter which calculates pagination from a TYPO3 Doctrine DBAL QueryBuilder.
 *
 * @template T
 * @implements AdapterInterface<T>
 */
class QueryBuilderAdapter implements AdapterInterface
{
    private QueryBuilder $queryBuilder;

    /**
     * @var callable
     * @phpstan-var callable(QueryBuilder): void
     */
    private $countQueryBuilderModifier;

    /**
     * @phpstan-param callable(QueryBuilder): void $countQueryBuilderModifier
     */
    public function __construct(QueryBuilder $queryBuilder, callable $countQueryBuilderModifier)
    {
        if ($queryBuilder->getType() !== DoctrineQueryBuilder::SELECT) {
            throw new InvalidArgumentException('Only SELECT queries can be paginated.');
        }

        $this->queryBuilder = clone $queryBuilder;
        $this->countQueryBuilderModifier = $countQueryBuilderModifier;
    }

    /**
     * @phpstan-return int<0, max>
     */
    public function getNbResults(): int
    {
        $qb = $this->prepareCountQueryBuilder();

        return (int) $qb->executeQuery()
            ->fetchOne();
    }

    /**
     * @phpstan-param int<0, max> $offset
     * @phpstan-param int<0, max> $length
     *
     * @return iterable<array-key, T>
     */
    public function getSlice(int $offset, int $length): iterable
    {
        $qb = clone $this->queryBuilder;

        return $qb->setMaxResults($length)
            ->setFirstResult($offset)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    private function prepareCountQueryBuilder(): QueryBuilder
    {
        $qb = clone $this->queryBuilder;
        $callable = $this->countQueryBuilderModifier;

        $callable($qb);

        return $qb;
    }
}
