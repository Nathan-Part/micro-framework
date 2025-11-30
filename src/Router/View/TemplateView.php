<?php

declare(strict_types=1);

namespace Framework312\Router\View;

use Framework312\Router\Request;
use Framework312\Template\Renderer;
use Symfony\Component\HttpFoundation\Response;

abstract class TemplateView extends BaseView
{
    protected Renderer $renderer;

    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;

        // On enregistre automatiquement le tag lié à cette View
        $this->renderer->register(static::class);
    }

    public static function use_template(): bool
    {
        return true; // TemplateView utilise des templates
    }

    /**
     * Chaque subclass DOIT retourner un template : "index.twig", "show.twig", etc.
     */
    abstract protected function template(Request $request): string;

    public function render(Request $request): Response
    {
        // 1. Déterminer la méthode HTTP (get, post…)
        $method = strtolower($request->getMethod());

        // 2. Vérifier que la méthode existe
        if (!method_exists($this, $method)) {
            return new Response("Méthode HTTP $method non supportée", 405);
        }

        // 3. Récupérer la data renvoyée par la méthode verbe
        $data = $this->$method($request);

        // 4. Déterminer le template à utiliser
        $templateName = $this->template($request);

        // 5. Rendu du template avec data
        $content = $this->renderer->render($templateName, $data);

        // 6. Retourner la Response
        return new Response($content, 200, [
            'Content-Type' => 'unknown',
        ]);
    }
}
