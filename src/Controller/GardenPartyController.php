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

namespace Lotgd\Bundle\GardenParty\Controller;

use Lotgd\Bundle\GardenParty\LotgdGardenPartyBundle;
use Lotgd\Bundle\GardenParty\Pattern;
use Lotgd\Core\Combat\Buffer;
use Lotgd\Core\Http\Response;
use Lotgd\Core\Lib\Settings;
use Lotgd\Core\Navigation\Navigation;
use Lotgd\Core\Output\Commentary;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class GardenPartyController extends AbstractController
{
    use Pattern\CheckPartyRunningTrait;
    use Pattern\ModuleUrlTrait;

    public const TRANSLATION_DOMAIN = LotgdGardenPartyBundle::TRANSLATION_DOMAIN;

    private $parameter;
    private $translator;
    private $buffer;
    private $settings;
    private $commentary;
    private $response;
    private $navigation;

    public function __construct(
        ParameterBagInterface $parameter,
        TranslatorInterface $translator,
        Buffer $buffer,
        Settings $settings,
        Commentary $commentary,
        Response $response,
        Navigation $navigation
    ) {
        $this->parameter  = $parameter;
        $this->translator = $translator;
        $this->buffer     = $buffer;
        $this->settings   = $settings;
        $this->commentary = $commentary;
        $this->response   = $response;
        $this->navigation = $navigation;
    }

    public function cake(): Response
    {
        global $session;

        //- See if the party is currently running.
        if ( ! $this->checkPartyRunning())
        {
            return $this->redirect('gardens.php');
        }

        $cakeToday = get_module_pref('cake_today', 'bundle_garden_party');
        $cost      = $this->parameter->get('lotgd_bundle.garden_party.cake.cost') * $session['user']['level'];

        $cake = $this->translator->trans('consumption.cake.name', [], self::TRANSLATION_DOMAIN);

        if ($session['user']['gold'] >= $cost)
        {
            $session['user']['gold'] -= $cost;

            set_module_pref('cake_today', $cakeToday + 1, 'bundle_garden_party');

            $this->buyedType($cake, 'cake');

            return redirect('gardens.php');
        }

        return $this->render('@LotgdGardenParty/cake.html.twig', $this->notHaveGold($cake));
    }

    public function drink(): Response
    {
        global $session;

        //- See if the party is currently running.
        if ( ! $this->checkPartyRunning())
        {
            return $this->redirect('gardens.php');
        }

        $drinksToday = get_module_pref('drinks_today', 'bundle_garden_party');
        $cost        = $this->parameter->get('lotgd_bundle.garden_party.drink.cost') * $session['user']['level'];

        $drink = $this->translator->trans('consumption.drink.name', [], self::TRANSLATION_DOMAIN);

        if ($session['user']['gold'] >= $cost)
        {
            $session['user']['gold'] -= $cost;

            set_module_pref('drinks_today', $drinksToday + 1, 'bundle_garden_party');

            $this->buyedType($drink, 'drink');

            return redirect('gardens.php');
        }

        return $this->render('@LotgdGardenParty/drink.html.twig', $this->notHaveGold($drink));
    }

    private function buyedType(string $name, string $type): void
    {
        $this->commentary->saveComment([
            'section' => 'gardens',
            'comment' => ': '.$this->translator->trans("consumption.{$type}.mote", [], self::TRANSLATION_DOMAIN),
        ]);

        $buff = [
            'name'     => $name,
            'atkmod'   => 1.05,
            'roundmsg' => $this->translator->trans("buff.msg.{$type}", ['name' => $name], self::TRANSLATION_DOMAIN),
            'rounds'   => 20,
            'schema'   => self::TRANSLATION_DOMAIN,
        ];

        $this->buffer->applyBuff("bundle_garden_party.{$type}", $buff);

        $buff = [
            'name'            => $this->translator->trans('buff.name.miss', [], self::TRANSLATION_DOMAIN),
            'minioncount'     => 1,
            'maxbadguydamage' => 0,
            'minbadguydamage' => 0,
            'effectnodmgmsg'  => $this->translator->trans('buff.msg.miss', [], self::TRANSLATION_DOMAIN),
            'rounds'          => -1,
            'schema'          => self::TRANSLATION_DOMAIN,
        ];

        $this->buffer->applyBuff('bundle_garden_party', $buff);
    }

    private function notHaveGold(string $name): array
    {
        $this->response->pageTitle('title', [
            'barman'  => $this->settings->getSetting('barkeep', '`tCedrik`0'),
            'clothes' => $this->translator->trans('section.hook.gardens.party.barman.clothes', [], self::TRANSLATION_DOMAIN),
        ], self::TRANSLATION_DOMAIN);

        $this->navigation->addNav('navigation.nav.return', 'gardens.php', ['textDomain' => self::TRANSLATION_DOMAIN]);

        return [
            'translation_domain' => self::TRANSLATION_DOMAIN,
            'buy_type'           => $name,
            'barman'             => $this->settings->getSetting('barkeep', '`tCedrik`0'),
            'party_type'         => $this->translator->trans('party.type', [], self::TRANSLATION_DOMAIN),
        ];
    }
}
