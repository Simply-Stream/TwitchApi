<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub;

use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\EventInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

/**
 * @template Tsubscription of Subscription
 * @template Tevent of EventInterface
 */
final readonly class MultipleEventResponse
{
    use SerializesModels;

    /**
     * @param Tsubscription $subscription  Metadata about the subscription.
     * @param Tevent[]      $events        Returns the user ID, user name, title, language, category ID, category name,
     *                                     and content classification labels for the given broadcaster.
     * @param string|null   $challenge     Your response must return a 200 status code, the response body must contain
     *                                     the raw challenge value, and you must set the Content-Type response header
     *                                     to the length of the challenge value
     */
    public function __construct(
        private Subscription $subscription,
        private ?array $events,
        private ?string $challenge = null
    ) {
    }

    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function getChallenge(): ?string
    {
        return $this->challenge;
    }
}
