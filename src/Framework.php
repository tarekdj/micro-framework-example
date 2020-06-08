<?php

namespace DIExample;

use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Framework
{
    const HTTP_GET = 'GET';

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;

    /**
     * @var \FastRoute\Dispatcher
     */
    protected $dispatcher;

    /**
     * @var array
     *
     * The routes definitions.
     */
    protected $routes;

    /**
     * Framework constructor.
     *
     * @param Request $request
     * @param Response $response
     * @throws \Exception
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->currentRequest = Request::createFromGlobals();
    }

    /**
     * Collects HTTP GET requests.
     *
     * @param string $route
     * @param callable $callback
     */
    public function get(string $route, callable $callback)
    {
        $this->routes[] = [
            'route' => $route,
            'callback' => $callback,
            'method' => self::HTTP_GET,
        ];
    }

    /**
     * Boot the framework.
     */
    public function boot()
    {
        // Build the router.
        $this->buildRoutes();
    }

    /**
     * Build the route collection and initialize the dispatcher.
     */
    protected function buildRoutes()
    {
        $routes = $this->routes;
        $this->dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) use($routes) {
            foreach ($routes as $route) {
                $r->addRoute($route['method'], $route['route'], $route['callback']);
            }
        });
    }

    /**
     * Handle the incoming request.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function run()
    {
        // Boot the framework.
        $this->boot();
        // Get the HTTP method and the uri.
        $httpMethod = $this->currentRequest->getRealMethod();
        $uri = $this->currentRequest->getRequestUri();

        // Strip query string (?foo=bar) and decode URI.
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        // Get the corresponding route.
        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // Route not found, return a 404 error.
                return $this->response->setContent('Not found!')
                    ->setStatusCode(404)
                    ->send();
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                // Method not allowed, return a 405 error.
                return $this->response->setContent('Not allowed!')
                  ->setStatusCode(405)
                  ->send();
                break;
            case \FastRoute\Dispatcher::FOUND:
                // Route found, handle the callback.
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                call_user_func($handler, $vars);
                break;
        }
    }
}
