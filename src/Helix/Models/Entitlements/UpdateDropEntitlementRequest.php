<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Entitlements;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;
use Webmozart\Assert\Assert;

final readonly class UpdateDropEntitlementRequest extends AbstractModel
{
    use SerializesModels;

    /**
     * @param string[]    $entitlementIds    A list of IDs that identify the entitlements to update. You may specify a
     *                                       maximum of 100 IDs.
     * @param string|null $fulfillmentStatus The fulfillment status to set the entitlements to. Possible values are:
     *                                       - CLAIMED — The user claimed the benefit.
     *                                       - FULFILLED — The developer granted the benefit that the user claimed.
     */
    public function __construct(
        private array $entitlementIds = [],
        private ?string $fulfillmentStatus = null
    ) {
        if (null !== $this->fulfillmentStatus) {
            Assert::inArray(
                $this->fulfillmentStatus,
                ['CLAIMED', 'FULFILLED'],
                'Fulfilment status got an invalid value. Allowed values are: %2$s, got %s'
            );
        }

        Assert::allString($this->entitlementIds, 'Only strings are allowed as entitlement ID');
        Assert::maxCount($this->entitlementIds, 100, 'You may specify a maximum of %2$s entitlement IDs');
    }

    public function getEntitlementIds(): array
    {
        return $this->entitlementIds;
    }

    public function getFulfillmentStatus(): ?string
    {
        return $this->fulfillmentStatus;
    }
}
