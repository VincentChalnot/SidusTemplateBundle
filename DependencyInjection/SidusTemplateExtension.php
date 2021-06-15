<?php
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

use Sidus\TemplateBundle\Event\Subscriber\TemplateSubscriber;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class SidusTemplateExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        foreach ($config['routes'] as &$route) {
            $route['parameters'] = array_merge($config['global_parameters'], $route['parameters']);
            $route['headers'] = array_merge($config['global_headers'], $route['headers']);
        }
        unset($route);

        $definition = $container->getDefinition(TemplateSubscriber::class);
        $definition->addMethodCall('setRoutes', ['$routes' => $config['routes']]);
    }
}
