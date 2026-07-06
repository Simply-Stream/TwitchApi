<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Helper;

use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class SymfonyDenormalizerFactory
{
    public static function create(): DenormalizerInterface
    {
        $reflectionExtractor = new ReflectionExtractor();
        $propertyInfo = new PropertyInfoExtractor(
            listExtractors: [$reflectionExtractor],
            typeExtractors: [new PhpDocExtractor(), $reflectionExtractor],
        );

        $serializer = new Serializer([
            new BackedEnumNormalizer(),
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            new ObjectNormalizer(
                nameConverter: new CamelCaseToSnakeCaseNameConverter(),
                propertyTypeExtractor: $propertyInfo,
            ),
        ]);

        return new class ($serializer) implements DenormalizerInterface {
            public function __construct(private readonly Serializer $serializer) {}

            public function denormalize(array $data, string $type): object
            {
                /** @var object */
                return $this->serializer->denormalize($data, $type);
            }
        };
    }
}
