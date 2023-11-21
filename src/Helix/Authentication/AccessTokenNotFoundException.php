<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Authentication;

use RuntimeException;
use Throwable;

class AccessTokenNotFoundException extends RuntimeException
{
    /**
     * @inheritdoc
     */
    protected $message = 'Could not find AccessTokenInterface with key: "{key}"';

    /**
     * @param string         $key
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $key, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->message = str_replace('{key}', $key, $this->message);

        parent::__construct($message ?? $this->message, $code, $previous);
    }
}
