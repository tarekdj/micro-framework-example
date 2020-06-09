<?php

namespace DIExample;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DI\ContainerBuilder;

class Application
{
    /**
     * @return \DIExample\Framework
     * @throws \Exception
     */
    public static function create()
    {
        // Build a Lightweight container.
        $builder = new ContainerBuilder();
        $builder->useAutowiring(false);
        $builder->useAnnotations(false);
        $container = $builder->build();

        // Add the required services to boot the framework.
        $container->set('request', new Request);
        $container->set('response', new Response);
        $container->set('current_request', Request::createFromGlobals());

        return new Framework($container);
    }
}