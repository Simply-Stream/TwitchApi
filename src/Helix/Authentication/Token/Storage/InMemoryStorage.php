<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Authentication\Token\Storage;

use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * @deprecated This storage is broken. The TwitchProvider will be replaced in the future
 */
class InMemoryStorage implements TokenStorageInterface
{
    /**
     * @param array $tokens Initial set of tokens
     */
    public function __construct(protected array $tokens = [])
    {
    }

    /**
     * @inheritDoc
     */
    public function save(string $key, AccessTokenInterface $token): AccessTokenInterface
    {
        $this->tokens->set($key, $token);

        return $token;
    }

    /**
     * @inheritDoc
     */
    public function remove(string $key): void
    {
        $this->tokens->remove($key);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null): ?AccessTokenInterface
    {
        if (!$this->has($key)) {
            return null;
        }

        return $this->tokens->get($key);
    }

    /**
     * @inheritdoc
     */
    public function has(string $key): bool
    {
        /** @var AccessTokenInterface|null $token */
        $token = $this->tokens->get($key);

        return $token && $token->getExpires() > time();
    }
}
