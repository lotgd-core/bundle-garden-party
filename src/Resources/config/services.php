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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lotgd\Bundle\GardenParty\Controller\GardenPartyController;
use Lotgd\Bundle\GardenParty\EventSubscriber\GardenPartySubscriber;
use Lotgd\Core\Combat\Buffer;
use Lotgd\Core\Http\Response;
use Lotgd\Core\Navigation\Navigation;
use Lotgd\Core\Output\Commentary;

return static function (ContainerConfigurator $container)
{
    $container->services()
        //-- Controllers
        ->set(GardenPartyController::class)
            ->autoconfigure()
            ->args([
                new ReferenceConfigurator('parameter_bag'),
                new ReferenceConfigurator('translator'),
                new ReferenceConfigurator(Buffer::class),
                new ReferenceConfigurator('lotgd_core.settings'),
                new ReferenceConfigurator(Commentary::class),
                new ReferenceConfigurator(Response::class),
                new ReferenceConfigurator(Navigation::class)
            ])
            ->call('setContainer', [
                new ReferenceConfigurator('service_container')
            ])
            ->tag('controller.service_arguments')

        //-- Event Subscribers
        ->set(GardenPartySubscriber::class)
            ->args([
                new ReferenceConfigurator('parameter_bag'),
                new ReferenceConfigurator('lotgd_core.settings'),
                new ReferenceConfigurator(Navigation::class),
                new ReferenceConfigurator('translator')
                ])
            ->tag('kernel.event_subscriber')
    ;
};
