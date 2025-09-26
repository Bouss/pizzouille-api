<?php

namespace App\Content\Infrastructure\Event;

use App\Content\Domain\Event\IngredientCreated;
use App\Shared\Application\Bus\Event\DomainEventSubscriberInterface;
use App\Shared\UI\Contracts\Message\IngredientCreated as IngredientCreatedMessage;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class PublishIngredientCreatedSubscriber implements DomainEventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(IngredientCreated $event): void
    {
        $this->messageBus->dispatch(new IngredientCreatedMessage(
            $event->ingredientId(),
            $event->code(),
            $event->name()->toArray(),
            $event->type()->value,
            $event->cost(),
            $event->createdAt()->format(\DateTimeInterface::ATOM),
        ));
    }

    public static function subscribedTo(): array
    {
        return [IngredientCreated::class];
    }
}
