<?php

namespace SimplyStream\TwitchApi\Tests\Helper\Builder;

use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Models\Moderation\ApiClient;
use SimplyStream\TwitchApi\Helix\Models\Moderation\EventSubApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\SymfonyDenormalizerFactory;

trait BuildsEventSubApi
{
    protected function buildApi(MockHttpClient $http): EventSubApi
    {
        $factory = new Psr17Factory();
        $apiClient = new ApiClient($http, $factory, $factory, 'client-id');

        return new EventSubApi($apiClient, SymfonyDenormalizerFactory::create());
    }
}
