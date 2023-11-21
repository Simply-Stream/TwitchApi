<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Models\ChannelPoints;

use SimplyStream\TwitchApiBundle\Helix\Models\AbstractModel;
use Webmozart\Assert\Assert;

final readonly class RedemptionStatusRequest extends AbstractModel
{
    /**
     * @param string $status The status to set the redemption to. Possible values are:
     *                       - CANCELED
     *                       - FULFILLED
     *                       Setting the status to CANCELED refunds the user’s channel points.
     */
    public function __construct(
        private string $status
    ) {
        Assert::inArray($this->status, ['CANCELED', 'FULFILLED']);
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
