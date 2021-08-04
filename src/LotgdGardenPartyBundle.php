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

namespace Lotgd\Bundle\GardenParty;

use Lotgd\Bundle\Contract\LotgdBundleInterface;
use Lotgd\Bundle\Contract\LotgdBundleTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LotgdGardenPartyBundle extends Bundle implements LotgdBundleInterface
{
    public const TRANSLATION_DOMAIN = 'bundle_garden_party';

    use LotgdBundleTrait;

    // 'drinkemote' => 'What will display in the conversation when you order drink?|takes a big swig of Grape Soda.',

    /**
     * @inheritDoc
     */
    public function getLotgdVersion(): string
    {
        return '0.1.0';
    }

    /**
     * @inheritDoc
     */
    public function getLotgdIcon(): ?string
    {
        return 'party';
    }

    /**
     * @inheritDoc
     */
    public function getLotgdDescription(): string
    {
        return 'Party in the Garden of all cities.';
    }
}
