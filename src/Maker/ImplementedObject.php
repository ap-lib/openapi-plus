<?php

namespace AP\OpenAPIPlus\Maker;

use AP\OpenAPIPlus\OpenAPIMakerInterface;
use AP\OpenAPIPlus\OpenAPIScheme;
use ReflectionNamedType;

class ImplementedObject implements OpenAPIMakerInterface
{
    public function getScheme(ReflectionNamedType $type): ?array
    {
        if (class_exists($type->getName())) {
            $class = $type->getName();
            if (is_subclass_of($class, OpenAPIScheme::class)) {
                return $class::openAPI();
            }
        }
        return null;
    }
}