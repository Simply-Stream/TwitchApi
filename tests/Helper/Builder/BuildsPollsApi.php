<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Helper\Builder;

use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\PollsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\SymfonyDenormalizerFactory;

trait BuildsPollsApi
{
    protected function buildApi(MockHttpClient $http): PollsApi
    {
        $factory = new Psr17Factory();
        $apiClient = new ApiClient($http, $factory, $factory, 'client-id');
        $serializer = SymfonyDenormalizerFactory::create();

        return new PollsApi($apiClient, $serializer, $serializer);
    }
}
