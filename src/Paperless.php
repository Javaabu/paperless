<?php

namespace Javaabu\Paperless;

use Javaabu\Paperless\Support\ApplicationRoutes;

class Paperless
{
    public static function routes(): void
    {
        ApplicationRoutes::routes();
    }

    public static function apiRoutes(): void
    {
        ApplicationRoutes::apiRoutes();
    }
}
