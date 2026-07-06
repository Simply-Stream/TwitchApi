<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Ads\Request;

use Webmozart\Assert\Assert;

final readonly class StartCommercialRequest
{
    private const int MIN_LENGTH = 1;
    private const int MAX_LENGTH = 180;

    /**
     * @param string $broadcasterId The ID of the partner or affiliate broadcaster that wants to run the commercial.
     *                              This ID must match the user ID found in the OAuth token.
     * @param int    $length        The length of the commercial to run, in seconds. Twitch tries to serve a commercial
     *                              that’s the requested length, but it may be shorter or longer. The maximum length
     *                              you should request is 180 seconds.
     */
    public function __construct(
        public string $broadcasterId,
        public int $length,
    ) {
        Assert::stringNotEmpty($this->broadcasterId, 'Broadcaster ID can\'t be empty');
        Assert::range(
            $this->length,
            self::MIN_LENGTH,
            self::MAX_LENGTH,
            sprintf(
                'A commercial length should be between %d and %d seconds long. Got "%s"',
                self::MIN_LENGTH,
                self::MAX_LENGTH,
                $this->length,
            ),
        );
    }
}
