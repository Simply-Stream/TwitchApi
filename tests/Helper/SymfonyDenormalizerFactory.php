<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Helper;

use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class SymfonyDenormalizerFactory
{
    public static function create(): DenormalizerInterface|NormalizerInterface
    {
        $reflectionExtractor = new ReflectionExtractor();
        $propertyInfo = new PropertyInfoExtractor(
            listExtractors: [$reflectionExtractor],
            typeExtractors: [new PhpDocExtractor(), $reflectionExtractor],
        );

        $serializer = new Serializer([
            new BackedEnumNormalizer(),
            new EmptyStringAwareDateTimeNormalizer(new DateTimeNormalizer()),
            new ArrayDenormalizer(),
            new ObjectNormalizer(
                nameConverter: new TwitchNameConverter(new CamelCaseToSnakeCaseNameConverter()),
                propertyTypeExtractor: $propertyInfo,
                defaultContext: [
                    // Twitch treats absent fields as "leave unchanged"; clearing uses
                    // sentinel values ("", "0", []), never null. Sending null would be
                    // an explicit instruction we never mean.
                    AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                ],
            ),
        ]);

        return new class ($serializer) implements DenormalizerInterface, NormalizerInterface {
            public function __construct(private readonly Serializer $serializer) {}

            public function denormalize(array $data, string $type): object
            {
                /** @var object */
                return $this->serializer->denormalize($data, $type);
            }

            public function normalize(object $data): array
            {
                return $this->serializer->normalize($data);
            }
        };
    }
}
