<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;

abstract class AbstractApi
{
    public function __construct(
        protected readonly ApiClientInterface $apiClient,
        protected readonly DenormalizerInterface $denormalizer,
        protected readonly NormalizerInterface $normalizer,
    ) {
    }

    /**
     * @template T of object
     * @param class-string<T> $responseType
     * @param array<string, mixed> $query
     * @return T
     */
    protected function get(string $path, string $responseType, AccessTokenInterface $token, array $query = []): object
    {
        return $this->denormalizer->denormalize(
            $this->apiClient->request('GET', $path, $token, $query),
            $responseType,
        );
    }

    /**
     * @template T of object
     * @param class-string<T> $responseType
     * @param array<string, mixed> $body
     * @param array<string, mixed> $query
     * @return T
     */
    protected function post(string $path, string $responseType, AccessTokenInterface $token, array $body = [], array $query = []): object
    {
        return $this->denormalizer->denormalize(
            $this->apiClient->request('POST', $path, $token, $query, $body),
            $responseType,
        );
    }

    /**
     * POST request for endpoints that return 204 No Content.
     *
     * @param array<string, mixed> $body
     * @param array<string, mixed> $query
     */
    protected function postWithoutResponse(string $path, AccessTokenInterface $token, array $body = [], array $query = []): void
    {
        $this->apiClient->request('POST', $path, $token, $query, $body);
    }

    /**
     * @template T of object
     * @param class-string<T> $responseType
     * @param array<string, mixed> $body
     * @param array<string, mixed> $query
     * @return T
     */
    protected function put(string $path, string $responseType, AccessTokenInterface $token, array $body = [], array $query = []): object
    {
        return $this->denormalizer->denormalize(
            $this->apiClient->request('PUT', $path, $token, $query, $body),
            $responseType,
        );
    }

    /**
     * PUT request for endpoints that return 204 No Content.
     *
     * @param array<string, mixed> $body
     * @param array<string, mixed> $query
     */
    protected function putWithoutResponse(string $path, AccessTokenInterface $token, array $body = [], array $query = []): void
    {
        $this->apiClient->request('PUT', $path, $token, $query, $body);
    }

    /**
     * @template T of object
     * @param class-string<T> $responseType
     * @return T
     */
    protected function patch(string $path, string $responseType, AccessTokenInterface $token, array $body = [], array $query = []): object
    {
        return $this->denormalizer->denormalize(
            $this->apiClient->request('PATCH', $path, $token, $query, $body),
            $responseType,
        );
    }

    /**
     * PATCH request for endpoints that return 204 No Content.
     *
     * @param array<string, mixed> $body
     * @param array<string, mixed> $query
     */
    protected function patchWithoutResponse(string $path, AccessTokenInterface $token, array $body = [], array $query = []): void
    {
        $this->apiClient->request('PATCH', $path, $token, $query, $body);
    }

    /** @param array<string, mixed> $query */
    protected function delete(string $path, AccessTokenInterface $token, array $query = []): void
    {
        $this->apiClient->request('DELETE', $path, $token, $query);
    }

    /**
     * @template T of object
     * @param class-string<T> $responseType
     * @param array<string, mixed> $query
     * @return T
     */
    protected function deleteWithResponse(string $path, string $responseType, AccessTokenInterface $token, array $query = []): object
    {
        return $this->denormalizer->denormalize(
            $this->apiClient->request('DELETE', $path, $token, $query),
            $responseType,
        );
    }
}
