<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Attributes;

use Attribute;
use InvalidArgumentException;
use ReflectionClass;
use SimplyStream\TwitchApi\EventSub\ConditionInterface;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class EventSubSubscription
{
    /**
     * @param class-string<ConditionInterface> $condition
     */
    public function __construct(
        public string $type,
        public string $version,
        public string $condition,
    ) {
    }

    /**
     * Reads the subscription metadata off an event class, so callers can work with the event class
     * instead of repeating type strings.
     *
     * @param class-string<EventInterface> $eventClass
     *
     * @throws InvalidArgumentException When the class carries no EventSubSubscription attribute.
     */
    public static function fromEvent(string $eventClass): self
    {
        $attributes = new ReflectionClass($eventClass)->getAttributes(self::class);

        if ([] === $attributes) {
            throw new InvalidArgumentException(
                sprintf('%s carries no %s attribute.', $eventClass, self::class),
            );
        }

        return $attributes[0]->newInstance();
    }
}
