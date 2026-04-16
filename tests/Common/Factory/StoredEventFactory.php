<?php

namespace App\Tests\Common\Factory;

use App\Shared\Domain\Event\StoredEvent;
use DateTimeImmutable;
use ReflectionClass;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<StoredEvent>
 */
final class StoredEventFactory extends PersistentObjectFactory
{
    public static function class(): string
    {
        return StoredEvent::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'aggregateId' => '00000000-0000-0000-0000-000000000000',
            'type' => 'App\Shared\Domain\Event\DomainEvent',
            'body' => '{}',
            'occurredOn' => new DateTimeImmutable('2000-01-01T00:00:00.000+00:00'),
        ];
    }

    public function withId(int $id): self
    {
        return $this->with(['id' => $id]);
    }

    public function withAggregateId(string $aggregateId): self
    {
        return $this->with(['aggregateId' => $aggregateId]);
    }

    public function withType(string $type): self
    {
        return $this->with(['type' => $type]);
    }

    public function withBody(string $body): self
    {
        return $this->with(['body' => $body]);
    }

    public function occurredOn(string $occurredOn): self
    {
        return $this->with(['occurredOn' => new DateTimeImmutable($occurredOn)]);
    }

    protected function initialize(): static
    {
        return $this->instantiateWith(function (array $attributes): StoredEvent {
            $reflection = new ReflectionClass(StoredEvent::class);
            $storedEvent = $reflection->newInstanceWithoutConstructor();

            $reflection->getProperty('type')->setValue($storedEvent, $attributes['type']);
            $reflection->getProperty('aggregateId')->setValue($storedEvent, $attributes['aggregateId']);
            $reflection->getProperty('body')->setValue($storedEvent, $attributes['body']);
            $reflection->getProperty('occurredOn')->setValue($storedEvent, $attributes['occurredOn']);

            if (isset($attributes['id'])) {
                $reflection->getProperty('id')->setValue($storedEvent, $attributes['id']);
            }

            return $storedEvent;
        });
    }
}
