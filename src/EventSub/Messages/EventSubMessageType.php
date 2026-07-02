<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Messages;

enum EventSubMessageType: string
{
    case Notification = 'notification';
    case ChallengeVerification = 'webhook_callback_verification';
    case Revocation = 'revocation';
}
