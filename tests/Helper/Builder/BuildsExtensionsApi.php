<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Helper\Builder;

use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\ExtensionsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\SymfonyDenormalizerFactory;

trait BuildsExtensionsApi
{
    protected function buildApi(MockHttpClient $http): ExtensionsApi
    {
        $factory = new Psr17Factory();
        $apiClient = new ApiClient($http, $factory, $factory, 'client-id');
        $serializer = SymfonyDenormalizerFactory::create();

        return new ExtensionsApi($apiClient, $serializer, $serializer);
    }
}
