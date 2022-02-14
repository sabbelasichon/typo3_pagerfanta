<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_pagerfanta" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\Typo3Pagerfanta\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterPagerfantaViewsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (! $container->hasDefinition('pagerfanta.view_factory')) {
            return;
        }

        $definition = $container->getDefinition('pagerfanta.view_factory');

        foreach ($container->findTaggedServiceIds('pagerfanta.view') as $serviceId => $arguments) {
            $alias = $arguments[0]['alias'] ?? $serviceId;

            $definition->addMethodCall('set', [$alias, new Reference($serviceId)]);
        }
    }
}
