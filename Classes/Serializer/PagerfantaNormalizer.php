<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\Serializer;

use InvalidArgumentException;
use Pagerfanta\PagerfantaInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class PagerfantaNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;


    public function normalize($object, string $format = null, array $context = []): array
    {
        if (! $object instanceof PagerfantaInterface) {
            throw new InvalidArgumentException(sprintf(
                'The object must be an instance of "%s".',
                PagerfantaInterface::class
            ));
        }

        return [
            'items' => $this->normalizer->normalize($object->getIterator(), $format, $context),
            'pagination' => [
                'current_page' => $object->getCurrentPage(),
                'has_previous_page' => $object->hasPreviousPage(),
                'has_next_page' => $object->hasNextPage(),
                'per_page' => $object->getMaxPerPage(),
                'total_items' => $object->getNbResults(),
                'total_pages' => $object->getNbPages(),
            ],
        ];
    }


    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof PagerfantaInterface;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
