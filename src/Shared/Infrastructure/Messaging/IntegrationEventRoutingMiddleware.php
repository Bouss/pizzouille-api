<?php

namespace App\Shared\Infrastructure\Messaging;

use App\Shared\Contracts\Message\IntegrationEvent;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

final class IntegrationEventRoutingMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        $isIncoming = null !== $envelope->last(ReceivedStamp::class);

        if (
            $message instanceof IntegrationEvent
            && !$isIncoming
            && null === $envelope->last(AmqpStamp::class)
        ) {
            $envelope = $envelope->with(new AmqpStamp($message::messageName()));
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
