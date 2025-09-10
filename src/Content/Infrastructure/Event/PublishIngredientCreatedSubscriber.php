<?php

namespace App\Content\Infrastructure\Event;

use App\Content\Domain\Event\IngredientCreated;
use App\Shared\Application\Bus\Event\DomainEventSubscriber;
use stdClass;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class PublishIngredientCreatedSubscriber implements DomainEventSubscriber
{
    public function __construct(
        private MessageBusInterface $notificationBus
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(IngredientCreated $event): void
    {
        $message = new stdClass();

        // TODO: Continue here

        $this->notificationBus->dispatch($message);
    }

    public static function subscribedTo(): array
    {
        return [IngredientCreated::class];
    }
}
