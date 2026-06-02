<?php

namespace App\Content\UI\Messaging;

use App\Content\Application\IndexIngredient\IndexIngredientCommand;
use App\Shared\Application\Bus\Command\CommandBusInterface;
use App\Shared\Contracts\Message\IngredientCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'content')]
final readonly class IndexIngredientWhenCreatedHandler
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(IngredientCreated $message): void
    {
        $this->commandBus->handle(new IndexIngredientCommand($message->ingredientId));
    }
}
