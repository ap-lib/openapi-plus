<?php

namespace AP\OpenAPIPlus;

interface OpenAPIModificator
{
    public function updateOpenAPIElement(array $spec): array;
}