<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Channels;

final readonly class ChannelInformation
{
    /**
     * @param string   $broadcasterId               An ID that uniquely identifies the broadcaster.
     * @param string   $broadcasterLogin            The broadcaster’s login name.
     * @param string   $broadcasterName             The broadcaster’s display name.
     * @param string   $broadcasterLanguage         The broadcaster’s preferred language. The value is an ISO 639-1
     *                                              two-letter language code (for example, en for English). The value
     *                                              is set to “other” if the language is not a Twitch supported
     *                                              language.
     * @param string   $gameName                    The name of the game that the broadcaster is playing or last
     *                                              played. The value is an empty string if the broadcaster has never
     *                                              played a game.
     * @param string   $gameId                      An ID that uniquely identifies the game that the broadcaster is
     *                                              playing or last played. The value is an empty string if the
     *                                              broadcaster has never played a game.
     * @param string   $title                       The title of the stream that the broadcaster is currently streaming
     *                                              or last streamed. The value is an empty string if the broadcaster
     *                                              has never streamed.
     * @param int      $delay                       The value of the broadcaster’s stream delay setting, in seconds.
     *                                              This field’s value defaults to zero unless 1) the request specifies
     *                                              a user access token, 2) the ID in the broadcaster_id query
     *                                              parameter matches the user ID in the access token, and 3) the
     *                                              broadcaster has partner status and they set a non-zero stream delay
     *                                              value.
     * @param string[] $tags                        The tags applied to the channel.
     * @param string[] $contentClassificationLabels The CCLs applied to the channel.
     * @param bool     $isBrandedContent            Boolean flag indicating if the channel has branded content.
     */
    public function __construct(
        public string $broadcasterId,
        public string $broadcasterLogin,
        public string $broadcasterName,
        public string $broadcasterLanguage,
        public string $gameName,
        public string $gameId,
        public string $title,
        public int $delay,
        public array $tags,
        public array $contentClassificationLabels,
        public bool $isBrandedContent
    ) {
    }
}
