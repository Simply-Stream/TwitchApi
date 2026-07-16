<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Helper\Builder;

use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Api\AnalyticsApi;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\SymfonyDenormalizerFactory;

trait BuildsAnalyticsApi
{
    protected function buildApi(MockHttpClient $http): AnalyticsApi
    {
        $factory = new Psr17Factory();
        $apiClient = new ApiClient($http, $factory, $factory, 'client-id');
        $serializer = SymfonyDenormalizerFactory::create();

        return new AnalyticsApi($apiClient, $serializer, $serializer);
    }
}
