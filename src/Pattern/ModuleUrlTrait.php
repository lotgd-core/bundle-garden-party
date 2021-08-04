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

namespace Lotgd\Bundle\GardenParty\Pattern;

use Lotgd\Bundle\GardenParty\Controller\GardenPartyController;

trait ModuleUrlTrait
{
    public function getModuleUrl(string $method, string $query = '')
    {
        return "runmodule.php?method={$method}&controller=".urlencode(GardenPartyController::class).$query;
    }
}
