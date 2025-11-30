<?php

declare(strict_types=1);

namespace Framework312\Router\View;

use Framework312\Router\Request;

class HelloView extends JSONView
{
    // On répond aux requêtes GET
    protected function get(Request $request): string
    {
        return 'Hello world';
    }
}
