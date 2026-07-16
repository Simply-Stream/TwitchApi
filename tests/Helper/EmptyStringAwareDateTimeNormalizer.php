<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Helper;

use ArrayObject;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Twitch sends "" instead of null for absent timestamps, e.g. Search\Channel.started_at
 * on offline channels, BannedUser.expires_at on permanent bans, or the date_range of
 * a Bits leaderboard with period=all. Symfony's DateTimeNormalizer rejects "".
 */
final readonly class EmptyStringAwareDateTimeNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function __construct(
        private NormalizerInterface&DenormalizerInterface $inner,
    ) {
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if ('' === $data) {
            return null;
        }

        return $this->inner->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = [],
    ): bool {
        return $this->inner->supportsDenormalization($data, $type, $format, $context);
    }

    public function normalize(
        mixed $data,
        ?string $format = null,
        array $context = [],
    ): ArrayObject|array|string|int|float|bool|null {
        return $this->inner->normalize($data, $format, $context);
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $this->inner->supportsNormalization($data, $format, $context);
    }

    public function getSupportedTypes(?string $format): array
    {
        return $this->inner->getSupportedTypes($format);
    }
}
