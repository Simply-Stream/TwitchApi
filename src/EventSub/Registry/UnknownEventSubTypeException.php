<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Registry;

final class UnknownEventSubTypeException extends \RuntimeException
{
    public function __construct(
        public readonly string $subscriptionType,
        public readonly string $subscriptionVersion,
    ) {
        parent::__construct(
            sprintf('No EventSub mapping registered for type "%s" version "%s".', $subscriptionType, $subscriptionVersion)
        );
    }
}
