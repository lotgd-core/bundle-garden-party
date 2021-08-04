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

use DateInterval;
use DateTime;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('lotgd_garden_party');

        $treeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
                ->info('Note: party duration is 24 hours always.')
            ->children()
                ->scalarNode('start')
                    ->defaultValue(null)
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(function ($value)
                        {
                            try
                            {
                                (new DateTime($value));
                            }
                            catch (\Throwable $th)
                            {
                                return true;
                            }

                            return false;
                        })
                        ->thenInvalid('%s not is a valid date for DateTime object.')
                    ->end()
                    ->info('When does the part start. Valid format for DateTime object')
                    ->example('2015-01-20 use this format YYYY-MM-DD')
                ->end()
                ->scalarNode('repeat')
                    ->defaultValue('P1Y')
                    ->info('How often is the party repeated? By default repeated every year')
                    ->example('http://php.net/manual/en/dateinterval.construct.php for examples of format')
                    ->validate()
                        ->ifTrue(function ($value)
                        {
                            try
                            {
                                (new DateInterval($value));
                            }
                            catch (\Throwable $th)
                            {
                                return true;
                            }

                            return false;
                        })
                        ->thenInvalid('%s cannot be parsed as an interval.')
                    ->end()
                ->end()
                ->arrayNode('cake')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('cost')
                            ->defaultValue(20)
                            ->min(0)
                            ->info('Cost per level for cake')
                        ->end()
                        ->integerNode('max')
                            ->defaultValue(3)
                            ->min(1)
                            ->info('How many slices of cake can a player buy in one day?')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('drink')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('cost')
                            ->defaultValue(20)
                            ->min(0)
                            ->info('Cost per level for drink')
                        ->end()
                        ->integerNode('max')
                            ->defaultValue(3)
                            ->min(1)
                            ->info('How many party drinks can a player buy in one day?')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
