<?php

namespace App\Application;

final class Toggles
{
    public static function is_true(string $key): bool
    {
        return filter_var(getenv($key), FILTER_VALIDATE_BOOLEAN);
    }

    public static function should_append_stats(): bool
    {
        return self::is_true('APP_APPEND_STATS');
    }
}
