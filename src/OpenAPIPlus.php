<?php

namespace AP\OpenAPIPlus;

use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use RuntimeException;

class OpenAPIPlus
{
    /**
     * @param array<OpenAPIMakerInterface> $makers
     */
    public function __construct(
        protected array $makers
    )
    {
    }

    /**
     * Make openapi+ scheme. Based array of ReflectionParameters or ReflectionProperties
     *
     * @param array<ReflectionParameter|ReflectionProperty> $params
     * @return array
     */
    public function scheme(array $params): array
    {
        $schema = [];
        foreach ($params as $param) {
            $name  = $param->getName();
            $type  = $param->getType();
            $field = [];
            if ($type instanceof ReflectionNamedType) {
                foreach ($this->makers as $maker) {
                    $field = $maker->getScheme($type);
                    if (is_array($field)) {
                        break;
                    }
                }
                if (is_null($field)) {
                    $field['type'] = class_exists($type->getName())
                        ? 'object'
                        : match ($type->getName()) {
                            'bool' => 'boolean',
                            'int' => 'integer',
                            'array' => 'object',
                            'float', 'double' => 'number',
                            default => $type->getName(),
                        };
                }
            } else {
                throw new RuntimeException('no implemented');
            }

            if ($param instanceof ReflectionParameter) {
                if ($param->isDefaultValueAvailable()) {
                    $field['default'] = $param->getDefaultValue();
                }
            } elseif ($param instanceof ReflectionProperty) {
                if ($param->hasDefaultValue()) {
                    $field['default'] = $param->getDefaultValue();
                }
            }

            foreach ($param->getAttributes() as $attr) {
                $attrName = $attr->getName();
                if (is_subclass_of($attrName, OpenAPIModificator::class)) {
                    $field = $attr->newInstance()->updateOpenAPIElement($field);
                }
            }

            if (!is_null($type) && $type->allowsNull() && isset($field['type'])) {
                if (is_string($field['type'])) {
                    $field['type'] = [$field['type'], "null"];
                } elseif (is_array($field['type'])) {
                    $field['type'][] = "null";
                }
            }

            $schema[$name] = $field;
        }
        return $schema;
    }
}