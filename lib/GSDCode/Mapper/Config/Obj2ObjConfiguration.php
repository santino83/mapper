<?php

/**
 * MIT License
 *
 * Copyright (c) 2017 Giorgio Maria Santini
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace GSDCode\Mapper\Config;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Description of Obj2ObjConfiguration
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
final class Obj2ObjConfiguration implements ConfigurationInterface
{
    
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root("gsdcode_obj2obj");
        
        $rootNode
            ->children()
                ->append($this->getSettingSection())
                ->append($this->getConvertersSection())
                ->append($this->getMappingsSection())
            ->end();
        
        return $treeBuilder;
    }
    
    /**
     * 
     * @return NodeDefinition
     */
    private function getSettingSection()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root("settings");
        
        $rootNode
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode("date_format")->cannotBeEmpty()->defaultValue(\DateTime::W3C)->end()
                ->end();
        
        return $rootNode;
    }

    /**
     * 
     * @return NodeDefinition
     */
    private function getMappingsSection()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root("mappings");
        
        $rootNode
            ->arrayPrototype()->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode("classA")->cannotBeEmpty()->isRequired()->end()
                    ->scalarNode("classB")->cannotBeEmpty()->isRequired()->end()
                    ->arrayNode("fields")
                        ->arrayPrototype()->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode("fieldA")->cannotBeEmpty()->isRequired()->end()
                                ->scalarNode("fieldB")->cannotBeEmpty()->isRequired()->end()
                                ->scalarNode("converter")->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        
        return $rootNode;
    }
    
    /**
     * 
     * @return NodeDefinition
     */
    private function getConvertersSection()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root("converters");
        
        $rootNode->scalarPrototype()->end();
        
        return $rootNode;
    }
    
}
