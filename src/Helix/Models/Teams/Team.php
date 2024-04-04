<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Teams;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Team
{
    use SerializesModels;

    /**
     * @param Member[]          $users               The list of team members.
     * @param DateTimeInterface $createdAt           The UTC date and time (in RFC3339 format) of when the team was
     *                                               created.
     * @param DateTimeInterface $updatedAt           The UTC date and time (in RFC3339 format) of the last time the
     *                                               team was updated.
     * @param string            $info                The team’s description. The description may contain formatting
     *                                               such as Markdown, HTML, newline (\n) characters, etc.
     * @param string            $thumbnailUrl        A URL to a thumbnail image of the team’s logo.
     * @param string            $teamName            The team’s name.
     * @param string            $teamDisplayName     The team’s display name.
     * @param string            $id                  An ID that identifies the team.
     * @param string|null       $backgroundImageUrl  A URL to the team’s background image.
     * @param string|null       $banner              A URL to the team’s banner.
     */
    public function __construct(
        private array $users,
        private DateTimeInterface $createdAt,
        private DateTimeInterface $updatedAt,
        private string $info,
        private string $thumbnailUrl,
        private string $teamName,
        private string $teamDisplayName,
        private string $id,
        private ?string $backgroundImageUrl = null,
        private ?string $banner = null,
    ) {
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getInfo(): string
    {
        return $this->info;
    }

    public function getThumbnailUrl(): string
    {
        return $this->thumbnailUrl;
    }

    public function getTeamName(): string
    {
        return $this->teamName;
    }

    public function getTeamDisplayName(): string
    {
        return $this->teamDisplayName;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBackgroundImageUrl(): ?string
    {
        return $this->backgroundImageUrl;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }
}
