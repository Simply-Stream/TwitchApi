<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Search;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Channel
{
    use SerializesModels;

    /**
     * @param string            $broadcasterLanguage  The ISO 639-1 two-letter language code of the language used by
     *                                                the broadcaster. For example, en for English. If the broadcaster
     *                                                uses a language not in the list of supported stream languages,
     *                                                the value is other.
     * @param string            $broadcasterLogin     The broadcaster’s login name.
     * @param string            $displayName          The broadcaster’s display name.
     * @param string            $id                   An ID that uniquely identifies the channel (this is the
     *                                                broadcaster’s ID).
     * @param bool              $isLive               A Boolean value that determines whether the broadcaster is
     *                                                streaming live. Is true if the broadcaster is streaming live;
     *                                                otherwise, false.
     * @param string[]          $tags                 The tags applied to the channel.
     * @param string            $thumbnailUrl         A URL to a thumbnail of the broadcaster’s profile image.
     * @param string            $title                The stream’s title. Is an empty string if the broadcaster didn’t
     *                                                set it.
     * @param DateTimeInterface $startedAt            The UTC date and time (in RFC3339 format) of when the broadcaster
     *                                                started streaming. The string is empty if the broadcaster is not
     *                                                streaming live.
     * @param string|null       $gameId               The ID of the game that the broadcaster is playing or last
     *                                                played.
     * @param string|null       $gameName             The name of the game that the broadcaster is playing or last
     *                                                played.
     */
    public function __construct(
        private string $broadcasterLanguage,
        private string $broadcasterLogin,
        private string $displayName,
        private string $id,
        private bool $isLive,
        private array $tags,
        private string $thumbnailUrl,
        private string $title,
        private DateTimeInterface $startedAt,
        private ?string $gameId = null,
        private ?string $gameName = null,
    ) {
    }

    public function getBroadcasterLanguage(): string
    {
        return $this->broadcasterLanguage;
    }

    public function getBroadcasterLogin(): string
    {
        return $this->broadcasterLogin;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isLive(): bool
    {
        return $this->isLive;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getThumbnailUrl(): string
    {
        return $this->thumbnailUrl;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStartedAt(): DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getGameId(): ?string
    {
        return $this->gameId;
    }

    public function getGameName(): ?string
    {
        return $this->gameName;
    }
}
