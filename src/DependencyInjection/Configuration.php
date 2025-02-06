<?php
/**
 * This file is part of the Short Hint | Google Translator project.
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
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ShortHint\GoogleTranslatorBundle');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->scalarNode('default_locale')->defaultValue('%kernel.default_locale%')->end()
            ->scalarNode('api_key')->defaultValue(null)->end()
            ->arrayNode('target_languages')
                ->beforeNormalization()->ifString()->then(function ($v) { return [$v]; })->end()
                ->prototype('scalar')->end()
                ->defaultValue(['en'])
            ->end();

        return $treeBuilder;
    }
}
