<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Predictions;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Predictor
{
    use SerializesModels;

    /**
     * @param string   $userId            An ID that identifies the viewer.
     * @param string   $userName          The viewer’s display name.
     * @param string   $userLogin         The viewer’s login name.
     * @param int|null $channelPointsUsed The number of Channel Points the viewer spent.
     * @param int|null $channelPointsWon  The number of Channel Points distributed to the viewer.
     */
    public function __construct(
        private string $userId,
        private string $userName,
        private string $userLogin,
        private ?int $channelPointsUsed = null,
        private ?int $channelPointsWon = null
    ) {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getUserLogin(): string
    {
        return $this->userLogin;
    }

    public function getChannelPointsUsed(): ?int
    {
        return $this->channelPointsUsed;
    }

    public function getChannelPointsWon(): ?int
    {
        return $this->channelPointsWon;
    }
}
