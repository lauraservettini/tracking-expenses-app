<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass, ReflectionNamedType;
use Framework\Exceptions\ContainerException;

class Container
{
    private array $definitions = [];
    private array $resolved = [];

    public function addDefinitions(array $newdefinitions)
    {
        $this->definitions = [...$this->definitions, ...$newdefinitions];
    }

    public function resolve(string $className)
    {
        $reflectionClass = new ReflectionClass($className);

        // controlla se la classe non è astratta
        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException("Class {$className} is not instantiable");
        }

        $constructor = $reflectionClass->getConstructor();

        // controlla se non è stato definito il costruttore nella classe
        if (!$constructor) {
            return new $className;
        }

        $params = $constructor->getParameters();

        // controlla se nel costruttore sono passati dei parametri
        if (count($params) === 0) {
            return new $className;
        }

        // la var $dependencies salverà le istanze e la lista dei parametri per il controller
        $dependencies = [];

        foreach ($params as $param) {
            $name = $param->getName();
            $type = $param->getType();

            // verifica se $type non è null
            if (!$type) {
                throw new ContainerException("Failed to resolve class {$className} because param {$name} is missing a type hint.");
            }

            // verifica se $type è un instanza o del tipo di ReflectionNamedType
            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                throw new ContainerException("Failed to resolve class {$className} because invalid param name.");
            }

            $dependencies[] = $this->get($type->getName());
        }

        // crea una nuova instanza della classe
        return $reflectionClass->newInstanceArgs($dependencies);
    }

    public function get(string $className)
    {
        if (!array_key_exists($className, $this->definitions)) {
            throw new ContainerException("Class {$className} does not exist in container.");
        }

        // verifica se il valore esiste e ritorna il valore che contiene le dependency per instanziare la classe
        // Applica alla classe Container il Singleton Pattern evitando la duplicazione dell'instanza TemplateEngine(con variabili globali del template)
        if (array_key_exists($className, $this->resolved)) {
            return $this->resolved[$className];
        }

        $factory = $this->definitions[$className];

        $dependency = $factory();

        $this->resolved[$className] = $dependency;

        return $dependency;
    }
}
