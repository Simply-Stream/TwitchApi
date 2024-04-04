<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class BlockedTerm
{
    use SerializesModels;

    /**
     * @param string            $broadcasterId  The broadcaster that owns the list of blocked terms.
     * @param string            $moderatorId    The moderator that blocked the word or phrase from being used in the
     *                                          broadcaster’s chat room.
     * @param string            $id             An ID that identifies this blocked term.
     * @param string            $text           The blocked word or phrase.
     * @param DateTimeInterface $createdAt      The UTC date and time (in RFC3339 format) that the term was blocked.
     * @param DateTimeInterface $updatedAt      The UTC date and time (in RFC3339 format) that the term was updated.
     *
     *                                          When the term is added, this timestamp is the same as created_at. The
     *                                          timestamp changes as AutoMod continues to deny the term
     * @param DateTimeInterface $expiresAt      The UTC date and time (in RFC3339 format) that the blocked term is set
     *                                          to expire. After the block expires, users may use the term in the
     *                                          broadcaster’s chat room.
     *
     *                                          This field is null if the term was added manually or was permanently
     *                                          blocked by AutoMod.
     */
    public function __construct(
        private string $broadcasterId,
        private string $moderatorId,
        private string $id,
        private string $text,
        private DateTimeInterface $createdAt,
        private DateTimeInterface $updatedAt,
        private DateTimeInterface $expiresAt,
    ) {
    }

    public function getBroadcasterId(): string
    {
        return $this->broadcasterId;
    }

    public function getModeratorId(): string
    {
        return $this->moderatorId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getExpiresAt(): DateTimeInterface
    {
        return $this->expiresAt;
    }
}
