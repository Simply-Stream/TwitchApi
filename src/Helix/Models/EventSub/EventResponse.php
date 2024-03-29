<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub;

use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\EventInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

/**
 * @template Tsubscription of Subscription
 * @template Tevent of EventInterface
 */
final readonly class EventResponse
{
    use SerializesModels;

    /**
     * @param Tsubscription $subscription  Metadata about the subscription.
     * @param Tevent|null   $event         Returns the user ID, user name, title, language, category ID, category name,
     *                                     and content classification labels for the given broadcaster. Can be null,
     *                                     when the subscription is created and checks for challenge.
     * @param string|null   $challenge     Your response must return a 200 status code, the response body must contain
     *                                     the raw challenge value, and you must set the Content-Type response header
     *                                     to the length of the challenge value
     *
     * @noinspection PhpDocSignatureInspection Tsubscription and Tevent will be typeof Subscription and EventInterface
     */
    public function __construct(
        private Subscription $subscription,
        private ?EventInterface $event = null,
        private ?string $challenge = null
    ) {
    }

    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }

    public function getEvent(): ?EventInterface
    {
        return $this->event;
    }

    public function getChallenge(): ?string
    {
        return $this->challenge;
    }
}
