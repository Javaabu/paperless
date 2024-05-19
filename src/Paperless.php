<?php

namespace Javaabu\Paperless;

use Javaabu\Paperless\Routing\ApplicationRoutes;

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

    public static function documentUpload(): void
    {
        ApplicationRoutes::documentUpload();
    }

    public static function documentsShow(): void
    {
        ApplicationRoutes::documentsShow();
    }
}
