<?php

namespace AP\OpenAPIPlus\Maker;

use AP\OpenAPIPlus\OpenAPIMakerInterface;
use AP\Scheme\OpenAPI;
use ReflectionNamedType;

class ImplementedObject implements OpenAPIMakerInterface
{
    public function getScheme(ReflectionNamedType $type): ?array
    {
        if (class_exists($type->getName())) {
            $class = $type->getName();
            if (is_subclass_of($class, OpenAPI::class)) {
                $object = $class::openAPI();
                return isset($object['type'])
                    ? $object
                    : [
                        'type'       => 'object',
                        'properties' => $object,
                    ];
            }
        }
        return null;
    }
}