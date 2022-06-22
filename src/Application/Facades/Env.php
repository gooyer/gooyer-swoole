<?php

declare(strict_types=1);

namespace Gooyer\Application\Facades;

use Gooyer\Container\Container;

final class Env
{
    private static ?array $env = null;

    public static function get(string $id, mixed $default = null): mixed
    {
        if (is_null(self::$env)) {
            $container = Container::instance();
            self::$env = $container->get("env");
        }

        return array_key_exists($id, self::$env) ? self::$env[$id] : $default;
    }
}
