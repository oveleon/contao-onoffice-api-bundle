<?php

declare(strict_types=1);

/*
 * This file is part of Oveleon ContaoOnofficeApi Bundle.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\ContaoOnofficeApiBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Oveleon\ContaoOnofficeApiBundle\ContaoOnofficeApiBundle;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Plugin implements BundlePluginInterface, RoutingPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoOnofficeApiBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class, RegionEntity::class])
                ->setReplace(['contao-onoffice-api-bundle']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        return $resolver
            ->resolve(__DIR__.'/../Resources/config/routes.yml')
            ->load(__DIR__.'/../Resources/config/routes.yml')
            ;
    }
}
