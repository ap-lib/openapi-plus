<?php

namespace AP\OpenAPIPlus\Modificator;

use AP\OpenAPIPlus\OpenAPIModificator;
use Attribute;

/**
 * Element won't add to example
 */
#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
class XScalarIgnore implements OpenAPIModificator
{
    public function updateOpenAPIElement(array $spec): array
    {
        $spec['x-scalar-ignore'] = true;
        return $spec;
    }
}