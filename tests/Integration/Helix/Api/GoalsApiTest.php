<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Goals\Request\GetCreatorGoalsRequest;
use SimplyStream\TwitchApi\Helix\Api\Goals\Response\CreatorGoalsResponse;
use SimplyStream\TwitchApi\Helix\Api\GoalsApi;
use SimplyStream\TwitchApi\Helix\Models\Goals\CreatorGoal;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsGoalsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(GoalsApi::class)]
final class GoalsApiTest extends TestCase
{
    use BuildsGoalsApi;

    #[Test]
    public function get_creator_goals_denormalizes_the_goal_list(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'                => '1woowvbkiNv8BRxEWSqmQz6Zk92',
                'broadcaster_id'    => '141981764',
                'broadcaster_name'  => 'TwitchDev',
                'broadcaster_login' => 'twitchdev',
                'type'              => 'follower',
                'description'       => 'Follow goal for Helix Development',
                'current_amount'    => 27062,
                'target_amount'     => 30000,
                'created_at'        => '2021-08-16T17:22:23Z',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getCreatorGoals(
            new GetCreatorGoalsRequest(broadcasterId: '141981764'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/goals', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=141981764', $request->getUri()->getQuery());

        $this->assertInstanceOf(CreatorGoalsResponse::class, $response);
        $this->assertCount(1, $response->data);

        $goal = $response->data[0];
        $this->assertInstanceOf(CreatorGoal::class, $goal);
        $this->assertSame('1woowvbkiNv8BRxEWSqmQz6Zk92', $goal->id);
        $this->assertSame('follower', $goal->type);
        $this->assertSame('Follow goal for Helix Development', $goal->description);
        $this->assertSame(27062, $goal->currentAmount);
        $this->assertSame(30000, $goal->targetAmount);
        $this->assertInstanceOf(DateTimeInterface::class, $goal->createdAt);
        $this->assertSame('2021-08-16', $goal->createdAt->format('Y-m-d'));
    }

    #[Test]
    public function get_creator_goals_accepts_an_empty_description(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'                => 'goal-1',
                'broadcaster_id'    => '141981764',
                'broadcaster_name'  => 'TwitchDev',
                'broadcaster_login' => 'twitchdev',
                'type'              => 'subscription_count',
                'description'       => '',
                'current_amount'    => 12,
                'target_amount'     => 50,
                'created_at'        => '2021-08-16T17:22:23Z',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getCreatorGoals(
            new GetCreatorGoalsRequest(broadcasterId: '141981764'),
            new StaticAccessToken(),
        );

        $goal = $response->data[0];
        $this->assertSame('', $goal->description);
        $this->assertSame('subscription_count', $goal->type);
    }

    #[Test]
    public function get_creator_goals_returns_an_empty_list_when_no_goals_exist(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getCreatorGoals(
            new GetCreatorGoalsRequest(broadcasterId: '141981764'),
            new StaticAccessToken(),
        );

        $this->assertSame([], $response->data);
    }
}
