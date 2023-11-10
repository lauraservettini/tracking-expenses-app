<?php

declare(strict_types=1);

namespace Framework;

class Router
{
    private array $routes = [];
    private array $middlewares = [];

    public function add(string $method, string $path, array $controller)
    {
        $path = $this->normalizePath($path);
        $this->routes[] = [
            "path" => $path,
            "method" => strtoupper($method),
            "controller" => $controller
        ];
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path, "/");
        $path = "/{$path}/";
        $path = preg_replace("#[/]{2,}#", "/", $path);

        return $path;
    }

    public function dispatch(string $path, string $method, Container $container = null)
    {
        $path = $this->normalizePath($path);
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if (
                !preg_match("#^{$route['path']}$#", $path) ||
                $route["method"] !== $method
            ) {
                continue;
            }

            // instanzia il controller da Router
            [$class, $function] = $route['controller'];

            // Viene utilizzato il Factory Design Pattern, evita l'uso di __construct tramite la dependency injection e l'uso delle Reflection API
            // controlla se c'è un container, nel container se la classe non è astratta, se c'è il costruttore, 
            // se ci sono parametri passati al costruttore e ritorna una nuova instanza della classe
            $controllerInstance =  $container ? $container->resolve($class) : new $class;

            $action = fn () => $controllerInstance->{$function}();

            foreach ($this->middlewares as $middleware) {
                // prima di utilizzarla si instanzia la  per poter utilizzare i metodi relativi
                $middlewareInstance = $container ? $container->resolve($middleware) : new $middleware;

                // riassegna all'azione il la funzione process della middleware e passa l'azione come $next;
                $action = fn () => $middlewareInstance->process($action);
            }

            $action();

            // aggiungere return previene che si attivi un'altra route
            return;
        }
    }

    // la middleware deve avere accesso al container per inserire le dependency
    // aggiunge la middleware alla lista (array) su Router.php
    public function addMiddleware(string $middleware)
    {
        $this->middlewares[] = $middleware;
    }
}
