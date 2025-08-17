<?php

namespace AP\OpenAPIPlus\Modificator;

use AP\OpenAPIPlus\OpenAPIModificator;

/**
 * Element won't add to example
 */
class ExampleReadOnly implements OpenAPIModificator
{
    public function updateOpenAPIElement(array $spec): array
    {
        $spec['readOnly'] = true;
        return $spec;
    }
}