<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;
use Webmozart\Assert\Assert;

final readonly class BanUser extends AbstractModel
{
    /**
     * @param string      $userId   The ID of the user to ban or put in a timeout.
     * @param int|null    $duration To ban a user indefinitely, don’t include this field.
     *
     *                              To put a user in a timeout, include this field and specify the timeout period, in
     *                              seconds. The minimum timeout is 1 second and the maximum is 1,209,600 seconds (2
     *                              weeks).
     *
     *                              To end a user’s timeout early, set this field to 1, or use the Unban user endpoint.
     * @param string|null $reason   The reason you’re banning the user or putting them in a timeout. The text is
     *                              user defined and is limited to a maximum of 500 characters.
     */
    public function __construct(
        private string $userId,
        private ?int $duration = null,
        private ?string $reason = null
    ) {
        if (null !== $this->duration) {
            Assert::greaterThanEq($this->duration, 1, 'The minimum timeout is %2$s second, got %s');
            Assert::lessThanEq($this->duration, 1_209_600, 'The maximum timeout is %2$s seconds, got %s');
        }

        if (null !== $this->reason) {
            Assert::maxLength(
                $this->reason,
                500,
                sprintf('The maximum length for a reason is %2$s characters, got %s', strlen($this->reason), 500)
            );
        }
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }
}
