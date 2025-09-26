<?php

namespace App\Shared\Infrastructure\Bus\Query;

use App\Shared\Application\Bus\Query\QueryBusInterface;
use App\Shared\Application\Bus\Query\QueryInterface;
use App\Shared\Application\Bus\Query\ResponseInterface;
use App\Shared\Infrastructure\Bus\Command\HandlerMapper;
use ReflectionException;
use RuntimeException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

final class SymfonyInMemoryQueryBus implements QueryBusInterface
{
    private MessageBus $bus;

    /**
     * @throws ReflectionException
     */
    public function __construct(iterable $queryHandlers)
    {
        $this->bus = new MessageBus([
            new HandleMessageMiddleware(new HandlersLocator(HandlerMapper::map($queryHandlers)))
        ]);
    }

    /**
     * @throws ExceptionInterface
     * @throws Throwable
     */
    public function ask(QueryInterface $query): ?ResponseInterface
    {
        try {
            $envelope = $this->bus->dispatch($query);

            /** @var HandledStamp|null $handled */
            $handled = $envelope->last(HandledStamp::class);

            return $handled?->getResult();
        } catch (NoHandlerForMessageException) {
            throw new RuntimeException(sprintf('No handle found for the query: "%s"', $query::class));
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious() ?? $e;
        }
    }
}
