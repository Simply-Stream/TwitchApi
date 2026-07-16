<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Registry;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;
use SimplyStream\TwitchApi\EventSub\EventInterface;

final class EventSubTypeRegistry
{
    /** @var array<string, array{condition: class-string<ConditionInterface>, event: class-string<EventInterface>}> */
    private array $map = [];

    /**
     * @param class-string<ConditionInterface> $conditionClass
     * @param class-string<EventInterface> $eventClass
     */
    public function register(string $type, string $version, string $conditionClass, string $eventClass): void
    {
        $this->map[$this->key($type, $version)] = [
            'condition' => $conditionClass,
            'event' => $eventClass,
        ];
    }

    /**
     * @return array{condition: class-string<ConditionInterface>, event: class-string<EventInterface>}
     */
    public function resolve(string $type, string $version): array
    {
        return $this->map[$this->key($type, $version)]
            ?? throw new UnknownEventSubTypeException($type, $version);
    }

    public function has(string $type, string $version): bool
    {
        return isset($this->map[$this->key($type, $version)]);
    }

    private function key(string $type, string $version): string
    {
        return "{$type}:{$version}";
    }
}
