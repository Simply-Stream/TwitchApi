<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

final readonly class AutoModSettings
{
    /**
     * @param string   $broadcasterId           The broadcaster’s ID.
     * @param string   $moderatorId             The moderator’s ID.
     * @param int|null $overallLevel            The default AutoMod level for the broadcaster. This field is null if
     *                                          the broadcaster has set one or more of the individual settings.
     * @param int      $disability              The Automod level for discrimination against disability.
     * @param int      $aggression              The Automod level for hostility involving aggression.
     * @param int      $sexualitySexOrGender    The AutoMod level for discrimination based on sexuality, sex, or gender.
     * @param int      $misogyny                The Automod level for discrimination against women.
     * @param int      $bullying                The Automod level for hostility involving name calling or insults.
     * @param int      $swearing                The Automod level for profanity.
     * @param int      $raceEthnicityOrReligion The Automod level for racial discrimination.
     * @param int      $sexBasedTerms           The Automod level for sexual content.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public ?int $overallLevel,
        public int $disability,
        public int $aggression,
        public int $sexualitySexOrGender,
        public int $misogyny,
        public int $bullying,
        public int $swearing,
        public int $raceEthnicityOrReligion,
        public int $sexBasedTerms,
    ) {
    }
}
