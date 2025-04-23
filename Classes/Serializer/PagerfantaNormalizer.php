<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\Serializer;

use Pagerfanta\PagerfantaInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Webmozart\Assert\Assert;

final class PagerfantaNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function normalize(mixed $data, string $format = null, array $context = []): array
    {
        Assert::isInstanceOf($data, PagerfantaInterface::class);
        return [
            'items' => $this->normalizer->normalize($data->getIterator(), $format, $context),
            'pagination' => [
                'current_page' => $data->getCurrentPage(),
                'has_previous_page' => $data->hasPreviousPage(),
                'has_next_page' => $data->hasNextPage(),
                'per_page' => $data->getMaxPerPage(),
                'total_items' => $data->getNbResults(),
                'total_pages' => $data->getNbPages(),
            ],
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof PagerfantaInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            PagerfantaInterface::class => true,
        ];
    }
}
