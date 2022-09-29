<?php

class Router {
    private $listRoute;

    public function __construct() {
        $stringRoute = file_get_contents('./config/route.json');
        $this->listRoute = json_decode($stringRoute);
    }

    public function findRoute($httpRequest, $basePath) {
        $URI = $httpRequest->getUrl();
        $start_URI = explode('/', $URI);
        array_pop($start_URI);
        $start_URI = implode('/', $start_URI);
        $end_URI = explode('/', $URI);
        $end_URI = end($end_URI);
        $end_URI = ucwords($end_URI);
        $URI = $start_URI.'/'.$end_URI;
        $url = str_replace($basePath, "", $URI);
        $method = $httpRequest->getMethod();

        $routeFound = array_filter($this->listRoute, function($route) use ($url, $method) {
            return preg_match("#^".$route->path."$#", $url) && $route->method == $method;
        });
        
        $numberRoute = count($routeFound);
        if ($numberRoute > 1) {
            throw new MultipleRouteFoundException();
        } else if ($numberRoute == 0) {
            throw new NoRouteFoundException($httpRequest);
        }
         else {
            return new Route(array_shift($routeFound));
        }
    }
    public function dumpRoute() {
        return $listRoute;
    }
}