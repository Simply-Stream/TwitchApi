<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Helper;

use SimplyStream\TwitchApi\Helix\Models\Analytics\ExtensionAnalytics;
use SimplyStream\TwitchApi\Helix\Models\Analytics\GameAnalytics;
use SimplyStream\TwitchApi\Helix\Models\Chat\Image;
use SimplyStream\TwitchApi\Helix\Models\Chat\Version;
use SimplyStream\TwitchApi\Helix\Models\Streams\Marker;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

/**
 * Handles Twitch field names that CamelCaseToSnakeCaseNameConverter cannot derive:
 * all-caps abbreviations (URL) and digit-suffixed keys (url_1x), where the converter
 * is blind to digit boundaries in both directions.
 *
 * Keyed by class, because the same PHP property name may map differently per model.
 */
final readonly class TwitchNameConverter implements NameConverterInterface
{
    /** @var array<class-string, array<string, string>> PHP property => Twitch JSON key */
    private const array OVERRIDES = [
        ExtensionAnalytics::class => [
            'url' => 'URL',
        ],
        GameAnalytics::class => [
            'url' => 'URL',
        ],
        Image::class => [
            'url1x' => 'url_1x',
            'url2x' => 'url_2x',
            'url4x' => 'url_4x',
        ],
        Version::class => [
            'imageUrl1x' => 'image_url_1x',
            'imageUrl2x' => 'image_url_2x',
            'imageUrl4x' => 'image_url_4x',
        ],
        Marker::class => [
            'url' => 'URL',
        ],
    ];

    public function __construct(
        private NameConverterInterface $inner,
    ) {
    }

    public function normalize(
        string $propertyName,
        ?string $class = null,
        ?string $format = null,
        array $context = [],
    ): string {
        if (null !== $class && isset(self::OVERRIDES[$class][$propertyName])) {
            return self::OVERRIDES[$class][$propertyName];
        }

        return $this->inner->normalize($propertyName, $class, $format, $context);
    }

    public function denormalize(
        string $propertyName,
        ?string $class = null,
        ?string $format = null,
        array $context = [],
    ): string {
        if (null !== $class) {
            $phpName = array_search($propertyName, self::OVERRIDES[$class] ?? [], true);

            if (false !== $phpName) {
                return $phpName;
            }
        }

        return $this->inner->denormalize($propertyName, $class, $format, $context);
    }
}
