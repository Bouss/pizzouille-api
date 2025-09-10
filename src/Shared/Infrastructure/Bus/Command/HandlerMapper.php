<?php

namespace App\Shared\Infrastructure\Bus\Command;

use LogicException;
use ReflectionException;
use ReflectionMethod;

class HandlerMapper
{
    /**
     * @param iterable<callable> $handlers
     *
     * @return array<string, array<callable>>
     *
     * @throws ReflectionException
     */
    public static function map(iterable $handlers): array
    {
        $map = [];

        foreach ($handlers as $handler) {
            if (!method_exists($handler, '__invoke')) {
                throw new LogicException(sprintf('Handler "%s" must have an __invoke method.', $handler::class));
            }

            $map[self::extractFirstParam($handler)][] = [$handler, '__invoke'];
        }

        return $map;
    }

    /**
     * @throws ReflectionException
     */
    private static function extractFirstParam(callable $handler): string
    {
        $params = new ReflectionMethod($handler, '__invoke')->getParameters();

        if (1 !== count($params)) {
            throw new LogicException(sprintf('__invoke method of handler "%s" must have one and only one parameter', $handler::class));
        }

        $paramType = $params[0]->getType();

        if (null === $paramType) {
            throw new LogicException(sprintf('Missing type hint for the parameter of __invoke method of handler "%s"', $handler::class));
        }

        return $paramType->getName();
    }
}
