<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Registry;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;

final class EventSubTypeRegistryBuilder
{
    /**
     * @param iterable<class-string> $eventClasses
     */
    public function build(iterable $eventClasses): EventSubTypeRegistry
    {
        $registry = new EventSubTypeRegistry();

        foreach ($eventClasses as $eventClass) {
            $reflection = new \ReflectionClass($eventClass);
            $attributes = $reflection->getAttributes(EventSubSubscription::class);

            if ($attributes === []) {
                continue;
            }

            /** @var EventSubSubscription $meta */
            $meta = $attributes[0]->newInstance();

            $registry->register($meta->type, $meta->version, $meta->condition, $eventClass);
        }

        return $registry;
    }
}
