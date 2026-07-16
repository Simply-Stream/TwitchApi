<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\EventSub;

enum SubscriptionStatus: string
{
    case Enabled = 'enabled';
    case WebhookCallbackVerificationPending = 'webhook_callback_verification_pending';
    case WebhookCallbackVerificationFailed = 'webhook_callback_verification_failed';
    case NotificationFailuresExceeded = 'notification_failures_exceeded';
    case AuthorizationRevoked = 'authorization_revoked';
    case ModeratorRemoved = 'moderator_removed';
    case UserRemoved = 'user_removed';
    case ChatUserBanned = 'chat_user_banned';
    case VersionRemoved = 'version_removed';
    case BetaMaintenance = 'beta_maintenance';
    case WebsocketDisconnected = 'websocket_disconnected';
    case WebsocketFailedPingPong = 'websocket_failed_ping_pong';
    case WebsocketReceivedInboundTraffic = 'websocket_received_inbound_traffic';
    case WebsocketConnectionUnused = 'websocket_connection_unused';
    case WebsocketInternalError = 'websocket_internal_error';
    case WebsocketNetworkTimeout = 'websocket_network_timeout';
    case WebsocketNetworkError = 'websocket_network_error';
    case WebsocketFailedToReconnect = 'websocket_failed_to_reconnect';
}
