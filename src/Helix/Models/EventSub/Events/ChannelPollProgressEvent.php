<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use DateTimeInterface;

final readonly class ChannelPollProgressEvent extends Event
{
    /**
     * @param string              $id                   ID of the poll.
     * @param string              $broadcasterUserId    The requested broadcaster ID.
     * @param string              $broadcasterUserLogin The requested broadcaster login.
     * @param string              $broadcasterUserName  The requested broadcaster display name.
     * @param string              $title                Question displayed for the poll.
     * @param array               $choices              An array of choices for the poll. Includes vote counts.
     * @param BitsVoting          $bitsVoting           Not supported.
     * @param ChannelPointsVoting $channelPointsVoting  The Channel Points voting settings for the poll.
     * @param DateTimeInterface   $startedAt            The time the poll started.
     * @param DateTimeInterface   $endsAt               The time the poll will end.
     */
    public function __construct(
        private string $id,
        private string $broadcasterUserId,
        private string $broadcasterUserLogin,
        private string $broadcasterUserName,
        private string $title,
        private array $choices,
        private BitsVoting $bitsVoting,
        private ChannelPointsVoting $channelPointsVoting,
        private DateTimeInterface $startedAt,
        private DateTimeInterface $endsAt
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBroadcasterUserId(): string
    {
        return $this->broadcasterUserId;
    }

    public function getBroadcasterUserLogin(): string
    {
        return $this->broadcasterUserLogin;
    }

    public function getBroadcasterUserName(): string
    {
        return $this->broadcasterUserName;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getChoices(): array
    {
        return $this->choices;
    }

    public function getBitsVoting(): BitsVoting
    {
        return $this->bitsVoting;
    }

    public function getChannelPointsVoting(): ChannelPointsVoting
    {
        return $this->channelPointsVoting;
    }

    public function getStartedAt(): DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getEndsAt(): DateTimeInterface
    {
        return $this->endsAt;
    }
}
