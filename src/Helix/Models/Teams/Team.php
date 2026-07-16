<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Teams;

use DateTimeInterface;

final readonly class Team
{
    /**
     * @param list<Member>      $users              The list of team members.
     * @param DateTimeInterface $createdAt          The UTC date and time (in RFC3339 format) of when the team was
     *                                              created.
     * @param DateTimeInterface $updatedAt          The UTC date and time (in RFC3339 format) of the last time the team
     *                                              was updated.
     * @param string            $info               The team’s description. The description may contain formatting
     *                                              such as Markdown, HTML, newline (\n) characters, etc.
     * @param string            $thumbnailUrl       A URL to a thumbnail image of the team’s logo.
     * @param string            $teamName           The team’s name.
     * @param string            $teamDisplayName    The team’s display name.
     * @param string            $id                 An ID that identifies the team.
     * @param string|null       $backgroundImageUrl A URL to the team’s background image.
     * @param string|null       $banner             A URL to the team’s banner.
     */
    public function __construct(
        public array $users,
        public DateTimeInterface $createdAt,
        public DateTimeInterface $updatedAt,
        public string $info,
        public string $thumbnailUrl,
        public string $teamName,
        public string $teamDisplayName,
        public string $id,
        public ?string $backgroundImageUrl = null,
        public ?string $banner = null,
    ) {
    }
}
