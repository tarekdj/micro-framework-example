<?php

namespace DIExample;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Application
{
    /**
     * @return \DIExample\Framework
     * @throws \Exception
     */
    public static function create()
    {
        return new Framework(new Request, new Response);
    }
}