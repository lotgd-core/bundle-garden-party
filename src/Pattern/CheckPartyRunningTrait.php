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

trait CheckPartyRunningTrait
{
    /**
     * Check that is party day.
     */
    private function checkPartyRunning()
    {
        $interval = new \DateInterval($this->parameter->get('lotgd_bundle.garden_party.repeat'));
        $start    = new \DateTime($this->parameter->get('lotgd_bundle.garden_party.start'));
        $end      = new \DateTime('now');

        $period = new \DatePeriod($start, $interval, $end);

        $periodArray = iterator_to_array($period);
        $lastPeriod  = end($periodArray);

        return (bool) (strtotime($lastPeriod->format('Y-m-d')) == strtotime($end->format('Y-m-d')));
    }

}
