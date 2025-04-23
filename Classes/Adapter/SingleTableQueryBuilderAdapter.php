<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\Adapter;

use Pagerfanta\Exception\InvalidArgumentException;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

/**
 * Extended TYPO3 Doctrine DBAL adapter which assists in building the count query modifier for a SELECT query on a
 * single table.
 *
 * @template T
 * @extends QueryBuilderAdapter<T>
 */
final class SingleTableQueryBuilderAdapter extends QueryBuilderAdapter
{
    /**
     * @param string $countField Primary key for the table in query, used in the count expression. Must include table alias.
     */
    public function __construct(QueryBuilder $queryBuilder, string $countField)
    {
        parent::__construct($queryBuilder, $this->createCountQueryModifier($countField));
    }

    private function createCountQueryModifier(string $countField): \Closure
    {
        $select = $this->createSelectForCountField($countField);

        return function (QueryBuilder $queryBuilder) use ($select): void {
            $queryBuilder->select($select)
                ->resetOrderBy()
                ->setMaxResults(1);
        };
    }

    private function createSelectForCountField(string $countField): string
    {
        if ($this->countFieldHasNoAlias($countField)) {
            throw new InvalidArgumentException('The $countField must contain a table alias in the string.');
        }

        return sprintf('COUNT(DISTINCT %s) AS total_results', $countField);
    }

    private function countFieldHasNoAlias(string $countField): bool
    {
        return ! str_contains($countField, '.');
    }
}
