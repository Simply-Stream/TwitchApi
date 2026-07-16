<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Teams\Request;

use Webmozart\Assert\Assert;

final readonly class GetTeamsRequest
{
    /**
     * @param string|null $name The name of the team to get. This parameter and the id parameter are mutually exclusive;
     *                         you must specify the team’s name or ID but not both.
     * @param string|null $id   The ID of the team to get. This parameter and the name parameter are mutually exclusive;
     *                         you must specify the team’s name or ID but not both.
     */
    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
    ) {
        // name and id are mutually exclusive; exactly one must be provided.
        Assert::false(
            $name === null && $id === null,
            'Either name or id must be provided.',
        );
        Assert::false(
            $name !== null && $id !== null,
            'name and id are mutually exclusive; provide only one.',
        );
    }
}
