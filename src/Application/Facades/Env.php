<?php

declare(strict_types=1);

namespace Gooyer\Application\Facades;

use Gooyer\Container\Container;

final class Env
{
    private static ?array $env = null;

    public static function get(string $id, mixed $default = null, string $type = "string"): mixed
    {
        if (is_null(self::$env)) {
            $container = Container::instance();
            self::$env = $container->get("env");
        }

        return self::cast(array_key_exists($id, self::$env) ? self::$env[$id] : $default, $type);
    }

    private static function cast(mixed $value, string $type): mixed
    {
        return match ($type) {
            "number", "int", "integer" => intval($value),
            "float" => floatval($value),
            default => strval($value),
        };
    }
}
