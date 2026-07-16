<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\AutomodTermsUpdateCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'automod.terms.update', version: '1', condition: AutomodTermsUpdateCondition::class)]
final readonly class AutomodTermsUpdateEvent implements EventInterface
{
    /**
     * @param string   $broadcasterUserId    The ID of the broadcaster specified in the request.
     * @param string   $broadcasterUserLogin The login of the broadcaster specified in the request.
     * @param string   $broadcasterUserName  The user name of the broadcaster specified in the request.
     * @param string   $moderatorUserId      The ID of the moderator who changed the channel settings.
     * @param string   $moderatorUserLogin   The moderator’s login.
     * @param string   $moderatorUserName    The moderator’s user name.
     * @param string   $action               The status change applied to the terms. Possible options are:
     *                                       - add_permitted
     *                                       - remove_permitted
     *                                       - add_blocked
     *                                       - remove_blocked
     * @param bool     $fromAutomod          Indicates whether this term was added due to an Automod message
     *                                       approve/deny action.
     * @param string[] $terms                The list of terms that had a status change.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $moderatorUserId,
        public string $moderatorUserLogin,
        public string $moderatorUserName,
        public string $action,
        public bool $fromAutomod,
        public array $terms,
    ) {
    }
}
