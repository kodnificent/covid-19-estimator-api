<?php

namespace Kodnificent\Covid19EstimatorApi;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Kodnificent\Covid19EstimatorApi\Http\Exception\MethodNotAllowedException;
use Kodnificent\Covid19EstimatorApi\Http\Exception\NotFoundException;

use function FastRoute\simpleDispatcher;

class RouteDispatcher
{
    /**
     * The request method
     * 
     * @var string
     */
    protected $req_method;

    /**
     * The request uri
     * 
     * @var string
     */
    protected $req_uri;

    /**
     * Controller namespace
     * 
     * @var string
     */
    protected $controller_namespace = 'Kodnificent\\Covid19EstimatorApi\\Http\\Controller';

    /**
     * Create an instance of the route dispatcher
     * 
     * @return $this
     */
    public function __construct()
    {
        $this->req_method = $this->getRequestMethod();

        $this->req_uri = $this->getRequestUri();

        return $this;
    }

    /**
     * Dispatch routes against the request method and uri
     * 
     * @return void
     */
    public function dispatch()
    {
        $dispatcher = simpleDispatcher(function(RouteCollector $route){
    
            return require __DIR__.'/../routes/routes.php';
        });
        
        $route_info = $dispatcher->dispatch($this->req_method, $this->req_uri);

        switch ($route_info[0]) {
            case Dispatcher::NOT_FOUND:
                throw new NotFoundException('Route not found');
            break;
            
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException('Method not allowed');
            break;

            case Dispatcher::FOUND:

                $vars = $route_info[2];

                $controller = explode('@', $route_info[1]);
                $controller_class = $this->controller_namespace ."\\$controller[0]";
                $controller_method = count($controller) > 1 ? $controller[1] : null;

                $handler = new $controller_class();

                if(!$controller_method)
                {
                    $handler;
                } else {
                    $handler->{$controller_method}($vars);
                }
            break;
        }
    }

    /**
     * Get the server request method
     * 
     * @return string
     */
    protected function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get the server request uri
     * 
     * @return string
     */
    protected function getRequestUri()
    {
        
        $req_uri = $_SERVER['REQUEST_URI'];

        // we have to strip the query string and decode
        // the request uri
        $query_pos = strpos($req_uri, '?');
        if($query_pos !== false)
        {
            $req_uri = substr($req_uri, 0, $query_pos);
        }

        $req_uri = rawurldecode($req_uri);

        return $req_uri;
    }
}