<?php /** @noinspection NullPointerExceptionInspection */
/*
 * This file is part of the Sidus/TemplateBundle package.
 *
 * Copyright (c) 2021 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sidus\TemplateBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sidus_template');

        /** @formatter:off */
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('global_parameters')
                    ->defaultValue([])
                    ->normalizeKeys(false)
                    ->variablePrototype()->end()
                ->end()
                ->arrayNode('global_headers')
                    ->defaultValue([])
                    ->normalizeKeys(false)
                    ->variablePrototype()->end()
                ->end()
            ->arrayNode('routes')
                ->normalizeKeys(false)
                ->useAttributeAsKey('action')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('template')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('status')->defaultValue(Response::HTTP_OK)->end()
                        ->arrayNode('parameters')
                            ->defaultValue([])
                            ->normalizeKeys(false)
                            ->variablePrototype()->end()
                        ->end()
                        ->arrayNode('headers')
                            ->defaultValue([])
                            ->normalizeKeys(false)
                            ->variablePrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        /** @formatter:on */

        return $treeBuilder;
    }
}
