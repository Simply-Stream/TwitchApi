<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\PollsApi;
use SimplyStream\TwitchApi\Helix\Models\Polls\Choice;
use SimplyStream\TwitchApi\Helix\Models\Polls\CreatePollRequest;
use SimplyStream\TwitchApi\Helix\Models\Polls\EndPollRequest;
use SimplyStream\TwitchApi\Helix\Models\Polls\Poll;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Tests\Helper\UserAwareFunctionalTestCase;

class PollsApiTest extends UserAwareFunctionalTestCase
{
    public function testGetPolls()
    {
        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $pollsApi = new PollsApi($apiClient);
        $getPollsResponse = $pollsApi->getPolls(
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:polls']))
        );

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getPollsResponse);
        $this->assertGreaterThan(0, count($getPollsResponse->getData()));
        $this->assertContainsOnlyInstancesOf(Poll::class, $getPollsResponse->getData());

        foreach ($getPollsResponse->getData() as $poll) {
            $this->assertInstanceOf(Poll::class, $poll);
            $this->assertNotEmpty($poll->getId());
            $this->assertIsString($poll->getId());

            $this->assertSame($testUser['id'], $poll->getBroadcasterId());
            $this->assertSame($testUser['display_name'], $poll->getBroadcasterName());
            $this->assertSame($testUser['login'], $poll->getBroadcasterLogin());

            $this->assertSame('Test title', $poll->getTitle());

            $this->assertIsArray($poll->getChoices());
            $this->assertContainsOnlyInstancesOf(Choice::class, $poll->getChoices());

            foreach ($poll->getChoices() as $index => $choice) {
                $this->assertIsString($choice->getId());
                $this->assertNotEmpty($choice->getId());

                $this->assertSame('Choice ' . ($index + 1), $choice->getTitle());
                $this->assertGreaterThan(0, $choice->getVotes());
                $this->assertGreaterThanOrEqual(0, $choice->getChannelPointsVotes());
                $this->assertSame(0, $choice->getBitsVotes());
            }

            $this->assertFalse($poll->isBitsVotingEnabled());
            $this->assertSame(0, $poll->getBitsPerVote());

            $this->assertIsBool($poll->isChannelPointsVotingEnabled());
            $this->assertGreaterThanOrEqual(0, $poll->getChannelPointsPerVote());

            $this->assertContains($poll->getStatus(), ['ACTIVE', 'COMPLETED', 'TERMINATED', 'ARCHIVED', 'MODERATED', 'INVALID']);
            $this->assertGreaterThanOrEqual(15, $poll->getDuration());

            $this->assertInstanceOf(\DateTimeInterface::class, $poll->getStartedAt());

            if ($poll->getStatus() === 'ACTIVE') {
                $this->assertNull($poll->getEndedAt());
            } else {
                $this->assertInstanceOf(\DateTimeInterface::class, $poll->getEndedAt());
            }
        }
    }

    public function testCreatePoll()
    {
        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $pollsApi = new PollsApi($apiClient);
        $createPollResponse = $pollsApi->createPoll(
            new CreatePollRequest(
                $testUser['id'],
                'New Poll',
                [['title' => 'Choice 1'], ['title' => 'Choice 2']],
                300
            ),
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:polls']))
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $createPollResponse);
        $this->assertNotEmpty($createPollResponse->getData());
        $this->assertContainsOnlyInstancesOf(Poll::class, $createPollResponse->getData());

        foreach ($createPollResponse->getData() as $poll) {
            $this->assertNotEmpty($poll->getId());
            $this->assertIsString($poll->getId());
            $this->assertSame($testUser['id'], $poll->getBroadcasterId());
            $this->assertSame($testUser['display_name'], $poll->getBroadcasterName());
            $this->assertSame($testUser['login'], $poll->getBroadcasterLogin());
            $this->assertSame('New Poll', $poll->getTitle());
            $this->assertIsArray($poll->getChoices());
            $this->assertContainsOnlyInstancesOf(Choice::class, $poll->getChoices());
            $this->assertCount(2, $poll->getChoices());

            foreach ($poll->getChoices() as $index => $choice) {
                $this->assertIsString($choice->getId());
                $this->assertNotEmpty($choice->getId());
                $this->assertSame('Choice ' . ($index + 1), $choice->getTitle());
                $this->assertSame(0, $choice->getVotes());
                $this->assertSame(0, $choice->getChannelPointsVotes());
                $this->assertSame(0, $choice->getBitsVotes());
            }

            $this->assertFalse($poll->isBitsVotingEnabled());
            $this->assertSame(0, $poll->getBitsPerVote());
            $this->assertFalse($poll->isChannelPointsVotingEnabled());
            $this->assertSame(0, $poll->getChannelPointsPerVote());
            $this->assertSame('ACTIVE', $poll->getStatus());
            $this->assertGreaterThanOrEqual(300, $poll->getDuration());
            $this->assertInstanceOf(\DateTimeInterface::class, $poll->getStartedAt());
            $this->assertNull($poll->getEndedAt());
        }
    }

    public function testEndPoll()
    {
        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $pollsApi = new PollsApi($apiClient);
        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:polls']));

        $getPollsResponse = $pollsApi->getPolls($testUser['id'], $accessToken);

        $endPollResponse = $pollsApi->endPoll(
            new EndPollRequest(
                $testUser['id'],
                $getPollsResponse->getData()[0]->getId(),
                'ARCHIVED'
            ),
            $accessToken
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $endPollResponse);
        $this->assertNotEmpty($endPollResponse->getData());
        $this->assertContainsOnlyInstancesOf(Poll::class, $endPollResponse->getData());

        foreach ($endPollResponse->getData() as $poll) {
            $this->assertNotEmpty($poll->getId());
            $this->assertIsString($poll->getId());
            $this->assertSame($testUser['id'], $poll->getBroadcasterId());
            $this->assertSame($testUser['display_name'], $poll->getBroadcasterName());
            $this->assertSame($testUser['login'], $poll->getBroadcasterLogin());
            $this->assertIsString($poll->getTitle());
            $this->assertNotEmpty($poll->getTitle());
            $this->assertIsArray($poll->getChoices());
            $this->assertContainsOnlyInstancesOf(Choice::class, $poll->getChoices());
            $this->assertCount(2, $poll->getChoices());

            foreach ($poll->getChoices() as $index => $choice) {
                $this->assertIsString($choice->getId());
                $this->assertNotEmpty($choice->getId());
                $this->assertSame('Choice ' . ($index + 1), $choice->getTitle());
                $this->assertIsInt($choice->getVotes());
                $this->assertIsInt($choice->getChannelPointsVotes());
                $this->assertSame(0, $choice->getBitsVotes());
            }

            $this->assertFalse($poll->isBitsVotingEnabled());
            $this->assertSame(0, $poll->getBitsPerVote());
            $this->assertFalse($poll->isChannelPointsVotingEnabled());
            $this->assertSame(0, $poll->getChannelPointsPerVote());
            $this->assertSame('ARCHIVED', $poll->getStatus());
            $this->assertGreaterThanOrEqual(300, $poll->getDuration());
            $this->assertInstanceOf(\DateTimeInterface::class, $poll->getStartedAt());
            $this->assertInstanceOf(\DateTimeInterface::class, $poll->getEndedAt());
        }
    }
}
