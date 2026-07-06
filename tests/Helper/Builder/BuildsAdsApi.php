<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Helper\Builder;

use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Models\Moderation\AdsApi;
use SimplyStream\TwitchApi\Helix\Models\Moderation\ApiClient;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\SymfonyDenormalizerFactory;

trait BuildsAdsApi
{
    protected function buildApi(MockHttpClient $http): AdsApi
    {
        $factory = new Psr17Factory();
        $apiClient = new ApiClient($http, $factory, $factory, 'client-id');

        return new AdsApi($apiClient, SymfonyDenormalizerFactory::create());
    }
}
