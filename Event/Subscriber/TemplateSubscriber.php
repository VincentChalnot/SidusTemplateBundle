<?php
/*
 * This file is part of the Sidus/MediaBundle package.
 *
 * Copyright (c) 2021 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sidus\TemplateBundle\Event\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Twig\Environment;

/**
 * Listens to view events and return a response if a template was configured for the action
 */
class TemplateSubscriber implements EventSubscriberInterface
{
    protected array $routes;

    public function __construct(
        protected Environment $twig
    ) {
    }


    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ViewEvent::class => '__invoke',
        ];
    }

    public function __invoke(ViewEvent $event): void
    {
        $route = $event->getRequest()->attributes->get('_route');
        if (!\array_key_exists($route, $this->routes)) {
            return;
        }
        $config = $this->routes[$route];
        $content = $this->twig->render(
            $config['template'],
            array_merge($config['parameters'], $event->getControllerResult())
        );

        $event->setResponse(
            new Response(
                content: $content,
                status: $config['status'],
                headers: $config['headers'],
            )
        );
    }
}
