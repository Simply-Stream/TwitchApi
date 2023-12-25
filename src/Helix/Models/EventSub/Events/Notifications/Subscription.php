<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Subscription
{
    use SerializesModels;

    /**
     * @param string $subTier          The type of subscription plan being used. Possible values are:
     *                                 - 1000 — First level of paid or Prime subscription
     *                                 - 2000 — Second level of paid subscription
     *                                 - 3000 — Third level of paid subscription
     * @param bool   $isPrime          Indicates if the subscription was obtained through Amazon Prime.
     * @param int    $durationMonths   The number of months the subscription is for.
     */
    public function __construct(
        private string $subTier,
        private bool $isPrime,
        private int $durationMonths
    ) {
    }

    public function getSubTier(): string
    {
        return $this->subTier;
    }

    public function isPrime(): bool
    {
        return $this->isPrime;
    }

    public function getDurationMonths(): int
    {
        return $this->durationMonths;
    }
}
