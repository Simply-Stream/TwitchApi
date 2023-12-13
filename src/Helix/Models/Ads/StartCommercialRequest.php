<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Ads;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final readonly class StartCommercialRequest extends AbstractModel
{
    private const MIN_LENGTH = 1;
    private const MAX_LENGTH = 180;

    /**
     * @param string $broadcasterId The ID of the partner or affiliate broadcaster that wants to run the commercial.
     *                              This ID must match the user ID found in the OAuth token.
     * @param int    $length        The length of the commercial to run, in seconds. Twitch tries to serve a commercial
     *                              that’s the requested length, but it may be shorter or longer. The maximum length
     *                              you should request is 180 seconds.
     */
    public function __construct(
        private string $broadcasterId,
        private int $length
    ) {
        $this->validateProperties();
    }

    public function getBroadcasterId(): string
    {
        return $this->broadcasterId;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateProperties(): void
    {
        Assert::stringNotEmpty($this->broadcasterId, 'Broadcaster ID can\'t be empty');
        Assert::greaterThanEq(
            $this->length,
            self::MIN_LENGTH,
            sprintf('A commercial should at least be %s second long. Got "%s"', self::MIN_LENGTH, $this->length)
        );
        Assert::lessThanEq(
            $this->length,
            self::MAX_LENGTH,
            sprintf('The maximum commercial length you should request is %s seconds. Got "%s"', self::MAX_LENGTH, $this->length)
        );
    }
}
