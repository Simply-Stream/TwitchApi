<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Schedule;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Category
{
    use SerializesModels;

    /**
     * @param string $id   An ID that identifies the category that best represents the content that the broadcaster plans to stream. For
     *                     example, the game’s ID if the broadcaster will play a game or the Just Chatting ID if the broadcaster will host
     *                     a talk show.
     * @param string $name The name of the category. For example, the game’s title if the broadcaster will play a game or Just Chatting if
     *                     the broadcaster will host a talk show.
     */
    public function __construct(
        private string $id,
        private string $name,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
