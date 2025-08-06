# AP\OpenAPIPlus

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

A PHP specification system for generating OpenAPI 3.1-compatible schemas from PHP classes, enhanced with extra features and macros.

**Compatible with:** [OpenAPI 3.1 Specification](https://spec.openapis.org/oas/v3.1.0.html)

## Highlights

* Seamlessly integrates with PHP attributes and validators
* Supports schema generation from constructors or properties
* Allows macro extensions for enums and custom types

---

## Installation

```bash
composer require ap-lib/openapi-plus
```

## Requirements

* PHP 8.3 or higher

---

## Getting Started

### 1. Initialize the OpenAPIPlus Loader

Create a loader class to configure the system with your custom object definitions and enums:

```php
class OpenAPI
{
    private static OpenAPIPlus $openAPIPlus;

    public static function get(): OpenAPIPlus
    {
        return self::$openAPIPlus ??= new OpenAPIPlus([
            new ImplementedObject(),
        ]);
    }
}
```

---

### 2. Use Traits to Generate Schemas

Choose between generating schemas from constructor arguments or class properties.

#### Constructor-Based Schema

```php
trait ByConstructorOpenAPI
{
    public static function openAPI(): array
    {
        return OpenAPI::get()->scheme(
            (new \ReflectionClass(static::class))
                ->getConstructor()
                ->getParameters()
        );
    }
}
```

#### Property-Based Schema

```php
trait PropertiesOpenAPI
{
    public static function openAPI(): array
    {
        return OpenAPI::get()->scheme(
            (new \ReflectionClass(static::class))
                ->getProperties()
        );
    }
}
```

---

### 3. Annotate and Generate

Apply the trait to your class and use attribute-based validators (e.g. from [ap-lib/validator](https://github.com/ap-lib/validator)):

> Example how to implement OpenAPIModificator: https://github.com/ap-lib/validator/blob/main/src/String/Email.php
```php
class SignUpBody
{
    use PropertiesOpenAPI;

    #[LabelCompany]
    public string $company_name;

    #[Email]
    public string $email;

    #[Password(min_length: 8)]
    public string $password;

    #[Timezone]
    public ?string $timezone = null;
}
```

Generate the OpenAPI schema:

```php
echo json_encode(
    value: SignUpBody::openAPI(),
    flags: JSON_PRETTY_PRINT
);
```

---

### Example Output

```json
{
  "type": "object",
  "properties": {
    "company_name": {
      "type": "string",
      "minLength": 1,
      "maxLength": 32
    },
    "email": {
      "type": "string",
      "format": "email"
    },
    "password": {
      "type": "string",
      "format": "password"
    },
    "timezone": {
      "type": ["string", "null"],
      "default": null,
      "maxLength": 30
    }
  },
  "required": [
    "company_name",
    "email",
    "password"
  ]
}
```

---
