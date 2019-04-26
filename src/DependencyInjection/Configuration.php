<?php
/**
 * This file is part of the Symfony translation project.
 *
 * (c) Sabri Hamda <sabri@hamda.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ShortHint\GoogleTranslatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ShortHint\GoogleTranslatorBundle');

        $rootNode
            ->children()
            ->scalarNode('default_locale')->defaultValue('%locale%')->end()
            ->scalarNode('api_key')->defaultValue(null)->end()
            ->arrayNode('target_languages')
                ->beforeNormalization()->ifString()->then(function ($v) { return [$v]; })->end()
                ->prototype('scalar')->end()
                ->defaultValue(['%locale%'])
            ->end();

        return $treeBuilder;
    }
}
