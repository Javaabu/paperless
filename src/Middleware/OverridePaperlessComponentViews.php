<?php

namespace Javaabu\Paperless\Middleware;

use Illuminate\Support\Facades\Config;

class OverridePaperlessComponentViews
{
    public function handle($request, $next, $view)
    {
        Config::set('paperless.views', $view);

        return $next($request);
    }
}
