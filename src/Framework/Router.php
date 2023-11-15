<?php

declare(strict_types=1);

namespace Framework;

class Router
{
    private array $routes = [];
    private array $middlewares = [];
    private array $errorHandler = [];

    public function add(string $method, string $path, array $controller)
    {
        $path = $this->normalizePath($path);

        // per supportare i route params toglie dal path le {}
        $regexPath = preg_replace('#{[^/]+}#', '([^/]+)', $path);

        $this->routes[] = [
            "path" => $path,
            "method" => strtoupper($method),
            "controller" => $controller,
            "middlewares" => [],
            "regexPath" => $regexPath
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
        $method = strtoupper($_POST['_METHOD'] ?? $method);

        foreach ($this->routes as $route) {
            // verifica se il path inserito è diverso dalla route e dal metodo esistenti
            // $paramValues verrà creata dai riferimenti nel path(&$matches)
            if (
                !preg_match("#^{$route['regexPath']}$#", $path, $paramValues) ||
                $route["method"] !== $method
            ) {
                continue;
            }

            // rimuove il primo elemento dell'array
            array_shift($paramValues);

            // estrae tutti i nomi dei parametri senza {}
            // $paramKeyss verrà creata dai riferimenti nel path(&$matches)
            preg_match_all('#{([^/]+)}#', $route['path'], $paramKeys);

            $paramKeys = $paramKeys[1];

            // mette insieme le chiavi dell'array con i valori dell' array 
            $params = array_combine($paramKeys, $paramValues);

            // instanzia il controller da Router
            [$class, $function] = $route['controller'];

            // Viene utilizzato il Factory Design Pattern, evita l'uso di __construct tramite la dependency injection e l'uso delle Reflection API
            // controlla se c'è un container, nel container se la classe non è astratta, se c'è il costruttore, 
            // se ci sono parametri passati al costruttore e ritorna una nuova instanza della classe
            $controllerInstance =  $container ? $container->resolve($class) : new $class;

            $action = fn () => $controllerInstance->{$function}($params);

            // crea un array con le middleware globali e le middleware associate alla route
            // esegue prima le middleware globali e poi le route middleware
            $allMiddlewares = [...$route['middlewares'], ...$this->middlewares];
            foreach ($allMiddlewares as $middleware) {
                // prima di utilizzarla si instanzia la  per poter utilizzare i metodi relativi
                $middlewareInstance = $container ? $container->resolve($middleware) : new $middleware;

                // riassegna all'azione il la funzione process della middleware e passa l'azione come $next;
                $action = fn () => $middlewareInstance->process($action);
            }

            $action();

            // aggiungere return previene che si attivi un'altra route
            return;
        }

        $this->dispatchNotFound($container);
    }

    // la middleware deve avere accesso al container per inserire le dependency
    // aggiunge la middleware alla lista (array) su Router.php
    public function addMiddleware(string $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function addRouteMiddleware(string $middleware)
    {
        $lastRouteKey = array_key_last($this->routes);
        $this->routes[$lastRouteKey]['middlewares'][] = $middleware;
    }

    public function setErrorHandler(array $controller)
    {
        $this->errorHandler = $controller;
    }

    public function dispatchNotFound(Container $container)
    {
        [$class, $function] = $this->errorHandler;

        $controllerInstance = $container ? $container->resolve($class) : new $class;

        $action = fn () => $controllerInstance->$function();

        foreach ($this->middlewares as $middleware) {
            $middlewareInstance = $container ? $container->resolve($middleware) : new $middleware;

            $action = fn () => $middlewareInstance->process($action);
        }

        $action();
    }
}
