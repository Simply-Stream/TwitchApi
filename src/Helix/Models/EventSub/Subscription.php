<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ConditionInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

readonly class Subscription
{
    use SerializesModels;

    /**
     * @param string                 $type       The subscription’s type.
     * @param string                 $version    The version number that identifies this definition of the
     *                                           subscription’s data.
     * @param ConditionInterface     $condition  The subscription’s parameter values. This is a string-encoded JSON
     *                                           object whose contents are determined by the subscription type.
     * @param Transport              $transport  The transport details used to send the notifications.
     * @param string|null            $id         An ID that identifies the subscription.
     * @param string|null            $status     The subscription’s status. The subscriber receives events only for
     *                                           enabled subscriptions. Possible values are:
     *                                           - enabled — The subscription is enabled.
     *                                           - webhook_callback_verification_pending — The subscription is pending
     *                                           verification of the specified callback URL (see Responding to a
     *                                           challenge request).
     * @param DateTimeInterface|null $createdAt  The date and time (in RFC3339 format) of when the subscription was
     *                                           created.
     */
    public function __construct(
        protected string $type,
        protected string $version,
        protected ConditionInterface $condition,
        protected Transport $transport,
        protected ?string $id = null,
        protected ?string $status = null,
        protected ?DateTimeInterface $createdAt = null
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getCondition(): ConditionInterface
    {
        return $this->condition;
    }

    public function getTransport(): Transport
    {
        return $this->transport;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }
}
