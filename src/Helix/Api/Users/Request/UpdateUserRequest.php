<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users\Request;

use Webmozart\Assert\Assert;

final readonly class UpdateUserRequest
{
    /**
     * @param string|null $description The string to update the channel’s description to. The description is limited to
     *                                a maximum of 300 characters.
     *
     *                                To remove the description, pass an empty string (this sends ?description= to the
     *                                API). Leaving this null omits the parameter entirely.
     */
    public function __construct(
        public ?string $description = null,
    ) {
        if ($description !== null) {
            Assert::maxLength($description, 300, 'A description can not be longer than 300 characters.');
        }
    }
}
