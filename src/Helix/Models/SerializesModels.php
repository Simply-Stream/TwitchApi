<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models;

use DateTimeImmutable;
use ReflectionClass;
use ReflectionException;

trait SerializesModels
{
    /**
     * @throws ReflectionException
     */
    public function toArray(): array
    {
        return self::convert($this);
    }

    /**
     * @throws ReflectionException
     */
    protected static function convert($object): array
    {
        if (is_object($object)) {
            $reflection = new ReflectionClass($object);
            $properties = $reflection->getProperties();
            $array = [];

            foreach ($properties as $property) {
                $value = $property->getValue($object);

                if (is_object($value) && method_exists($value, 'toArray')) {
                    $array[strtolower(
                        ltrim(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $property->getName()), '_')
                    )] = $value->toArray();
                } else {
                    if ($value instanceof DateTimeImmutable) {
                        $array[strtolower(
                            ltrim(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $property->getName()), '_')
                        )] = $value->format(DATE_RFC3339_EXTENDED);
                    } else {
                        $array[strtolower(
                            ltrim(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $property->getName()), '_')
                        )] = $value;
                    }
                }
            }

            return $array;
        }

        return $object;
    }
}
