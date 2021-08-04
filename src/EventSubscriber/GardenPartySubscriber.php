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

namespace Lotgd\Bundle\GardenParty\EventSubscriber;

use Lotgd\Bundle\GardenParty\LotgdGardenPartyBundle;
use Lotgd\Bundle\GardenParty\Pattern;
use Lotgd\Core\Event\Core;
use Lotgd\Core\Events;
use Lotgd\Core\Lib\Settings;
use Lotgd\Core\Navigation\Navigation;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

class GardenPartySubscriber implements EventSubscriberInterface
{
    use Pattern\CheckPartyRunningTrait;
    use Pattern\ModuleUrlTrait;

    public const TRANSLATION_DOMAIN = LotgdGardenPartyBundle::TRANSLATION_DOMAIN;

    private $parameter;
    private $setting;
    private $navigation;
    private $translator;

    public function __construct(
        ParameterBagInterface $parameter,
        Settings $setting,
        Navigation $navigation,
        TranslatorInterface $translator
    ) {
        $this->parameter  = $parameter;
        $this->setting    = $setting;
        $this->navigation = $navigation;
        $this->translator = $translator;
    }

    public function newday(): void
    {
        set_module_pref('cake_today', 0, 'bundle_garden_party');
        set_module_pref('drinks_today', 0, 'bundle_garden_party');
    }

    public function garden(GenericEvent $event): void
    {
        global $session;

        //-- See if the party is currently running.
        if ( ! $this->checkPartyRunning())
        {
            return;
        }

        $this->navigation->setTextDomain(self::TRANSLATION_DOMAIN);

        $params = [
            'translation_domain' => self::TRANSLATION_DOMAIN,
            'barman'             => $this->setting->getSetting('barkeep', '`tCedrik`0'),
        ];

        $args['includeTemplatesPost']['@LotgdGardenParty/garden_party_info.html.twig'] = $params;

        $this->navigation->addHeader('navigation.category.party');

        $cakeToday   = get_module_pref('cake_today', 'bundle_garden_party');
        $drinkstoday = get_module_pref('drinks_today', 'bundle_garden_party');
        $cakeCost    = $this->parameter->get('lotgd_bundle.garden_party.cake.cost')  * $session['user']['level'];
        $drinkCost   = $this->parameter->get('lotgd_bundle.garden_party.drink.cost') * $session['user']['level'];

        if ($cakeToday < $this->parameter->get('lotgd_bundle.garden_party.cake.max') && $session['user']['gold'] >= $cakeCost)
        {
            $cake = $this->translator->trans('consumption.cake.name', [], self::TRANSLATION_DOMAIN);
            $this->navigation->addNav('navigation.nav.consumption', $this->getModuleUrl('cake'), [
                'params' => ['name' => $cake, 'cost' => $cakeCost],
            ]);
        }

        if ($drinkstoday < $this->parameter->get('lotgd_bundle.garden_party.drink.max') && $session['user']['gold'] >= $drinkCost)
        {
            $drink = $this->translator->trans('consumption.drink.name', [], self::TRANSLATION_DOMAIN);
            $this->navigation->addNav('navigation.nav.consumption', $this->getModuleUrl('drink'), [
                'params' => ['name' => $drink, 'cost' => $drinkCost],
            ]);
        }

        $this->navigation->setTextDomain();
    }

    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Core::NEWDAY             => 'newday',
            Events::PAGE_GARDEN_POST => 'garden',
        ];
    }
}
