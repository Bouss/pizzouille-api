<?php

namespace App\Content\Infrastructure\Event;

use App\Content\Domain\Event\IngredientCreated;
use App\Shared\Application\Bus\Event\DomainEventSubscriberInterface;
use App\Shared\UI\Contracts\Message\IngredientCreated as IngredientCreatedMessage;
use DateTimeInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class PublishIngredientCreatedSubscriber implements DomainEventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(IngredientCreated $event): void
    {
        $this->messageBus->dispatch(new IngredientCreatedMessage(
            $event->id(),
            $event->code(),
            $event->name()->toArray(),
            $event->type()->value,
            $event->cost()->value(),
            $event->createdAt()->format(DateTimeInterface::ATOM),
        ));
    }

    /**
     * @return array<class-string<IngredientCreated>>
     */
    public static function subscribedTo(): array
    {
        return [IngredientCreated::class];
    }
}
