<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\EventSub;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Object\DynamicConstructor;
use CuyZ\Valinor\Mapper\Source\Exception\InvalidSource;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\Mapper\Tree\Message\Messages;
use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\MapperBuilder;
use DateTimeImmutable;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use SimplyStream\TwitchApi\Helix\Api\EventSubApi;
use SimplyStream\TwitchApi\Helix\EventSub\Exceptions\ChallengeMissingException;
use SimplyStream\TwitchApi\Helix\EventSub\Exceptions\InvalidSignatureException;
use SimplyStream\TwitchApi\Helix\EventSub\Exceptions\MissingHeaderException;
use SimplyStream\TwitchApi\Helix\EventSub\Exceptions\UnsupportedEventException;
use SimplyStream\TwitchApi\Helix\Models\EventSub\EventResponse;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\EventInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\MultipleEventResponse;
use SimplyStream\TwitchApi\Helix\Models\EventSub\PaginatedEventSubResponse;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions\DropEntitlementGrantSubscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions\Subscriptions;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

use function array_key_exists;
use function current;
use function hash_hmac;

class EventSubService
{
    public const WEBHOOK_CALLBACK_MESSAGE_SIGNATURE_HEADER = 'Twitch-Eventsub-Message-Signature';
    public const WEBHOOK_CALLBACK_MESSAGE_TIMESTAMP = 'Twitch-Eventsub-Message-Timestamp';
    public const WEBHOOK_CALLBACK_EVENT_TYPE = 'Twitch-Eventsub-Subscription-Type';
    public const WEBHOOK_CALLBACK_MESSAGE_ID = 'Twitch-Eventsub-Message-Id';
    public const WEBHOOK_ENABLED = 'enabled';
    public const WEBHOOK_CALLBACK_VERIFICATION = 'webhook_callback_verification';
    public const WEBHOOK_CALLBACK_VERIFICATION_PENDING = 'webhook_callback_verification_pending';
    public const WEBHOOK_CALLBACK_VERIFICATION_FAILED = 'webhook_callback_verification_failed';
    public const WEBHOOK_NOTIFICATION_FAILURES_EXCEEDED = 'notification_failures_exceeded';
    public const WEBHOOK_AUTHORIZATION_REVOKED = 'authorization_revoked';
    public const WEBHOOK_USER_REVOKED = 'user_removed';

    protected TreeMapper $mapper;

    /**
     * @param EventSubApi   $eventSubApi
     * @param MapperBuilder $mapperBuilder
     * @param array         $options
     */
    public function __construct(
        protected EventSubApi $eventSubApi,
        MapperBuilder $mapperBuilder,
        protected array $options
    ) {
        // @TODO: Refactor to make MapperBuilder::registerConstructor accessible from outside - or at least
        //        make it possible to adjust/extend it
        $this->mapper = $mapperBuilder
            ->registerConstructor(fn (string $time): DateTimeImmutable => new DateTimeImmutable($time))
            ->registerConstructor(
                #[DynamicConstructor]
                function (string $className, array $value): Subscription {
                    $type = Subscriptions::MAP[$value['type']];

                    return new $type(
                        $value['condition'],
                        new Transport(...$value['transport']),
                        $value['id'],
                        $value['status'],
                        new DateTimeImmutable($value['createdAt']),
                    );
                }
            )
            ->allowPermissiveTypes()
            ->mapper();
    }

    /**
     * Create a subscription on EventSub API
     *
     * @template T of Subscription
     *
     * @param T                    $subscription
     * @param AccessTokenInterface $accessToken
     *
     * @return PaginatedEventSubResponse<T[]>
     */
    public function subscribe(Subscription $subscription, AccessTokenInterface $accessToken): PaginatedEventSubResponse
    {
        try {
            $response = $this->eventSubApi->createEventSubSubscription($subscription, $accessToken);
        } catch (MappingError $e) {
            throw new RuntimeException($e->getMessage());
        }

        return $response;
    }

    /**
     * Unsubscribes from a subscription with $subscriptionId
     *
     * @param string $subscriptionId
     *
     * @return void
     * @deprecated
     */
    public function unsubscribe(string $subscriptionId): void
    {
        // $this->eventSubApi->deleteEventSubSubscription($subscriptionId);
    }

    /**
     * Returns all current subscriptions from Twitch
     *
     * @return void
     * @deprecated
     */
    public function getSubscriptions(): void
    {
        // return $this->eventSubApi->getEventSubSubscriptions();
    }

    /**
     * Handles the callback send by Twitch. Will return raw json if Twitch send a verification request or maps the
     * response to an EventInterface. Will throw an exception on unsupported events or invalid signature. Note: Do not
     * forget to respond to Twitch with the raw challenge, that'll be provided in this methods response.
     *
     * @param RequestInterface $request
     * @param string|null      $secret
     *
     * @return MultipleEventResponse|EventResponse
     *
     * @throws InvalidSignatureException
     * @throws InvalidSource
     * @throws MappingError
     * @throws UnsupportedEventException
     * @throws ChallengeMissingException
     */
    public function handleSubscriptionCallback(
        RequestInterface $request,
        ?string $secret = null
    ): MultipleEventResponse|EventResponse {
        $this->verifySignature($request, $secret);
        $type = $this->extractType($request);

        try {
            /** @var MultipleEventResponse|EventResponse $eventResponse */
            $eventResponse = $this->mapper->map(
                sprintf(
                    '%s<%s, %s>',
                    $type === DropEntitlementGrantSubscription::TYPE ? MultipleEventResponse::class : EventResponse::class,
                    Subscriptions::MAP[$type],
                    EventInterface::AVAILABLE_EVENTS[$type]
                ),
                Source::json((string)$request->getBody())->camelCaseKeys()
            );
        } catch (MappingError $mappingError) {
            $messages = Messages::flattenFromNode($mappingError->node())->errors();
            foreach ($messages as $message) {
                echo $message . PHP_EOL;
            }

            throw $mappingError;
        }

        if ($eventResponse->getSubscription()->getStatus() === self::WEBHOOK_CALLBACK_VERIFICATION_PENDING && !$eventResponse->getChallenge()) {
            throw new ChallengeMissingException('Challenge is missing');
        }

        return $eventResponse;
    }

    /**
     * Verify signature sent in Twitch request to subscription verification callback
     *
     * @param RequestInterface $request
     * @param string|null      $secret
     *
     * @return true
     * @throws InvalidSignatureException
     */
    public function verifySignature(RequestInterface $request, ?string $secret = null): true
    {
        $content = (string)$request->getBody();
        $receivedSignature = current($request->getHeader(self::WEBHOOK_CALLBACK_MESSAGE_SIGNATURE_HEADER));

        $messageId = current($request->getHeader(self::WEBHOOK_CALLBACK_MESSAGE_ID));
        $timestamp = current($request->getHeader(self::WEBHOOK_CALLBACK_MESSAGE_TIMESTAMP));

        if (!$receivedSignature || !$timestamp || !$messageId) {
            throw new MissingHeaderException('Signature, Timestamp or MessageID headers empty');
        }

        $signature = 'sha256=' . hash_hmac(
            'sha256',
            $messageId . $timestamp . $content,
            $secret ?? $this->options['webhook']['secret']
        );

        if ($signature !== $receivedSignature) {
            throw new InvalidSignatureException(
                sprintf('Signature is invalid. Got "%s" expected "%s"', $receivedSignature, $signature)
            );
        }

        return true;
    }

    /**
     * Extract the event type from headers. Throws exception if event is not supported.
     *
     * @param RequestInterface $request
     *
     * @return string
     * @throws UnsupportedEventException
     */
    protected function extractType(RequestInterface $request): string
    {
        $type = current($request->getHeader(self::WEBHOOK_CALLBACK_EVENT_TYPE));
        if (!$type || !array_key_exists($type, EventInterface::AVAILABLE_EVENTS)) {
            throw new UnsupportedEventException(sprintf('The received event "%s" is not supported', $type));
        }

        return $type;
    }
}
