<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\AutomodSettingsUpdateCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'automod.settings.update', version: '1', condition: AutomodSettingsUpdateCondition::class)]
final readonly class AutomodSettingsUpdateEvent implements EventInterface
{
    /**
     * @param string   $broadcasterUserId       The ID of the broadcaster specified in the request.
     * @param string   $broadcasterUserLogin     The login of the broadcaster specified in the request.
     * @param string   $broadcasterUserName      The user name of the broadcaster specified in the request.
     * @param string   $moderatorUserId          The ID of the moderator who changed the channel settings.
     * @param string   $moderatorUserLogin       The moderator’s login.
     * @param string   $moderatorUserName        The moderator’s user name.
     * @param int      $bullying                 The Automod level for hostility involving name calling or insults.
     * @param int|null $overallLevel             The default AutoMod level for the broadcaster. This field is null if
     *                                           the broadcaster has set one or more of the individual settings.
     * @param int      $disability               The Automod level for discrimination against disability.
     * @param int      $raceEthnicityOrReligion  The Automod level for racial discrimination.
     * @param int      $misogyny                 The Automod level for discrimination against women.
     * @param int      $sexualitySexOrGender     The AutoMod level for discrimination based on sexuality, sex, or
     *                                           gender.
     * @param int      $aggression               The Automod level for hostility involving aggression.
     * @param int      $sexBasedTerms            The Automod level for sexual content.
     * @param int      $swearing                 The Automod level for profanity.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $moderatorUserId,
        public string $moderatorUserLogin,
        public string $moderatorUserName,
        public int $bullying,
        public ?int $overallLevel,
        public int $disability,
        public int $raceEthnicityOrReligion,
        public int $misogyny,
        public int $sexualitySexOrGender,
        public int $aggression,
        public int $sexBasedTerms,
        public int $swearing,
    ) {
    }
}
