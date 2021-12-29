<?php

/*
 * This file is part of the NucleosProfileBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\ProfileBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('nucleos_profile');

        $rootNode = $treeBuilder->getRootNode();

        $this->addRegistrationSection($rootNode);
        $this->addServiceSection($rootNode);

        return $treeBuilder;
    }

    private function addRegistrationSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('registration')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('confirmation')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultFalse()->end()
                                ->scalarNode('from_email')->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addServiceSection(ArrayNodeDefinition $node): void
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('service')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('mailer')->defaultValue('nucleos_profile.mailer.default')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
