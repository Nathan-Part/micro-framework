<?php

declare(strict_types=1);

namespace Framework312\Router\View;

use Framework312\Router\Request;
use Symfony\Component\HttpFoundation\Response;

class HTMLView extends BaseView
{
    public static function use_template(): bool
    {
        return false;
    }

    public function render(Request $request): Response
    {
        // 1. Trouver la méthode HTTP (get, post, etc.)
        $method = strtolower($request->getMethod());

        // 2. Vérifier que la méthode existe dans la View
        if (!method_exists($this, $method)) {
            return new Response(
                "Méthode HTTP $method non supportée",
                405,
                ['Content-Type' => 'text/html']
            );
        }

        // 3. Appeler la méthode (get(), post(), …)
        $data = $this->$method($request);

        // 4. Retourner une réponse HTML simple
        return new Response((string) $data, 200, [
            'Content-Type' => 'text/html',
        ]);
    }
}
