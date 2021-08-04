<?php

/**
 * This file is part of "LoTGD Bundle Garden Party".
 *
 * @see https://github.com/lotgd-core/bundle-garden-party
 *
 * @license https://github.com/lotgd-core/bundle-garden-party/blob/main/LICENSE
 * @author IDMarinas
 *
 * @since 0.1.0
 */

namespace Lotgd\Bundle\GardenParty\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class LotgdGardenPartyExtension extends ConfigurableExtension
{
    public function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__).'/Resources/config'));

        $loader->load('services.php');

        $container->setParameter('lotgd_bundle.garden_party.start', $mergedConfig['start']);
        $container->setParameter('lotgd_bundle.garden_party.repeat', $mergedConfig['repeat']);

        $container->setParameter('lotgd_bundle.garden_party.cake.cost', $mergedConfig['cake']['cost']);
        $container->setParameter('lotgd_bundle.garden_party.cake.max', $mergedConfig['cake']['max']);

        $container->setParameter('lotgd_bundle.garden_party.drink.cost', $mergedConfig['drink']['cost']);
        $container->setParameter('lotgd_bundle.garden_party.drink.max', $mergedConfig['drink']['max']);
    }
}
