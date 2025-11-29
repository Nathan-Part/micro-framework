<?php

declare(strict_types=1);

namespace Framework312\Router\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JSONView extends BaseView
{
    public static function use_template(): bool
    {
        return false;
    }

    public function render(Request $request): Response
    {
        // 1. Trouver la méthode HTTP (get, post, etc.)
        $method = strtolower($request->getMethod());

        if (!method_exists($this, $method)) {
            return new Response(
                json_encode(['error' => "Méthode HTTP $method non supportée"], JSON_THROW_ON_ERROR),
                405,
                ['Content-Type' => 'application/json']
            );
        }

        // 2. Appeler la méthode verbe
        $data = $this->$method($request);

        // 3. Encoder en JSON
        $json = json_encode($data, JSON_THROW_ON_ERROR);

        // 4. Retourner une Response JSON
        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
