<?php

namespace Framework312\Router\View;

use Framework312\Router\Request;

class BookView extends HTMLView
{
    protected function get(Request $request): string
    {
        $id = $request->attributes->get('id');
        return "Tu as demandé le livre ID : " . $id;
    }
}
