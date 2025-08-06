<?php

namespace AP\OpenAPIPlus;

use ReflectionNamedType;

interface OpenAPIMakerInterface
{
    public function getScheme(ReflectionNamedType $type): ?array;
}