<?php

declare(strict_types=1);

namespace Framework312\Router\View;

use Framework312\Router\Request;
use Framework312\Template\Renderer;
use Symfony\Component\HttpFoundation\Response;

abstract class TemplateView extends BaseView
{
    protected Renderer $renderer;
    protected string $templateName; // Ajout de la propriété pour le nom du template par défaut

    // Le constructeur prend désormais le Renderer et le nom du template
    public function __construct(Renderer $renderer, string $templateName)
    {
        $this->renderer = $renderer;
        $this->templateName = $templateName; // Stockage du nom du template

        // On enregistre automatiquement le tag lié à cette View
        $this->renderer->register(static::class);
    }

    public static function use_template(): bool
    {
        return true; // TemplateView utilise des templates
    }

    /**
     * Chaque subclass DOIT retourner un array de données (data) pour le template.
     */
    abstract protected function template(Request $request): array;

    public function render(Request $request): Response
    {
        // 1. Déterminer la méthode HTTP (get, post…)
        $method = strtolower($request->getMethod());

        // 2. Vérifier que la méthode existe
        if (!method_exists($this, $method)) {
            return new Response("Méthode HTTP $method non supportée", 405);
        }

        // 3. Récupérer la data renvoyée par la méthode verbe
        // La méthode verbe appelle la méthode template() pour obtenir la data.
        // NOTE: La méthode verbe (get) doit retourner un array (la data du template).
        $data = $this->$method($request);

        // 4. Déterminer le template à utiliser
        // NOTE: L'implémentation de BookView utilise directement la méthode get pour retourner les données.
        // Nous allons utiliser le templateName défini au constructeur
        $templateName = $this->templateName;

        // 5. Rendu du template avec data
        // L'implémentation de TwigRenderer utilise render(template, data)
        $content = $this->renderer->render($templateName, $data);

        // 6. Retourner la Response
        return new Response($content, 200, [
            // Le Content-Type pour le template (HTML)
            'Content-Type' => 'text/html',
        ]);
    }
}
