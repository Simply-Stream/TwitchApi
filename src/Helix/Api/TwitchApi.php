<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

final readonly class TwitchApi
{
    public function __construct(
        public AnalyticsApi $analyticsApi,
        public BitsApi $bitsApi,
        public ChannelPointsApi $channelPointsApi,
        public ChannelsApi $channelsApi,
        public CharityApi $charityApi,
        public ChatApi $chatApi,
        public ClipsApi $clipsApi,
        public AdsApi $adsApi,
        public EntitlementsApi $entitlementsApi,
        public EventSubApi $eventSubApi,
        public ExtensionsApi $extensionsApi,
        public GamesApi $gamesApi,
        public GoalsApi $goalsApi,
        public HypeTrainApi $hypeTrainApi,
        public ModerationApi $moderationApi,
        public PollsApi $pollsApi,
        public PredictionsApi $predictionsApi,
        public RaidsApi $raidsApi,
        public ScheduleApi $scheduleApi,
        public SearchApi $searchApi,
        public StreamsApi $streamsApi,
        public SubscriptionsApi $subscriptionsApi,
        public TeamsApi $teamsApi,
        public UsersApi $usersApi,
        public VideosApi $videosApi,
        public WhispersApi $whispersApi,
    ) {
    }
}
