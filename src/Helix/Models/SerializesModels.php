<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models;

use DateTimeInterface;
use ReflectionClass;
use ReflectionException;

trait SerializesModels
{
    /**
     * @param non-empty-string $property
     *
     * @return string
     */
    public static function toSnakeCase(string $property): string
    {
        return strtolower(
            ltrim(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $property), '_')
        );
    }

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
        $reflection = new ReflectionClass($object);
        $properties = $reflection->getProperties();
        $array = [];

        foreach ($properties as $property) {
            $value = $property->getValue($object);
            if (is_object($value) && method_exists($value, 'toArray')) {
                $array[self::toSnakeCase($property->getName())] = $value->toArray();
            } elseif (is_array($value)) {
                $arrayOfObjects = [];
                foreach ($value as $key => $objectElement) {
                    $key = is_string($key) ? self::toSnakeCase($key) : $key;

                    if (is_object($objectElement) && method_exists($objectElement, 'toArray')) {
                        $arrayOfObjects[$key] = $objectElement->toArray();
                    } else {
                        $arrayOfObjects[$key] = $objectElement;
                    }
                }
                $array[self::toSnakeCase($property->getName())] = $arrayOfObjects;
            } else {
                if ($value instanceof DateTimeInterface) {
                    $array[self::toSnakeCase($property->getName())] = $value->format(DATE_RFC3339_EXTENDED);
                } else {
                    $array[self::toSnakeCase($property->getName())] = $value;
                }
            }
        }

        return $array;
    }
}
