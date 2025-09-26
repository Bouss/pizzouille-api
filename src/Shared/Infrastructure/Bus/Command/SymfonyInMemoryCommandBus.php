<?php

namespace App\Shared\Infrastructure\Bus\Command;

use App\Shared\Application\Bus\Command\CommandBusInterface;
use App\Shared\Application\Bus\Command\CommandInterface;
use App\Shared\Infrastructure\Bus\Command\Middleware\DomainEventsMiddleware;
use ReflectionException;
use RuntimeException;
use Symfony\Bridge\Doctrine\Messenger\DoctrineTransactionMiddleware;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

readonly class SymfonyInMemoryCommandBus implements CommandBusInterface
{
    private MessageBus $bus;

    /**
     * @throws ReflectionException
     */
    public function __construct(
        iterable $commandHandlers,
        private DoctrineTransactionMiddleware $doctrineTransactionMiddleware,
        private DomainEventsMiddleware $domainEventsMiddleware,
    ) {
        $this->bus = new MessageBus([
            $this->doctrineTransactionMiddleware,
            $this->domainEventsMiddleware,
            new HandleMessageMiddleware(new HandlersLocator(HandlerMapper::map($commandHandlers)))
        ]);
    }

    /**
     * @throws ExceptionInterface
     * @throws Throwable
     */
    public function handle(CommandInterface $command): mixed
    {
        try {
            $envelope = $this->bus->dispatch($command);

            /** @var HandledStamp|null $handled */
            $handled = $envelope->last(HandledStamp::class);

            return $handled?->getResult();
        } catch (NoHandlerForMessageException) {
            throw new RuntimeException(sprintf('No handler found for the command "%s"', $command::class));
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious() ?? $e;
        }
    }
}
