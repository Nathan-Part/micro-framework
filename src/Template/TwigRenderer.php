<?php

declare(strict_types=1);

namespace Framework312\Template;

use Twig\Loader\FilesystemLoader; // Import de la classe Twig Loader

class TwigRenderer implements Renderer
{
    private string $templatesFolder;
    private FilesystemLoader $loader; // Changement du type pour garder le loader
    private \Twig\Environment $twig;

    public function __construct(string $templatesFolder)
    {
        $this->templatesFolder = $templatesFolder;

        // On instancie le Loader une seule fois
        $this->loader = new FilesystemLoader($templatesFolder);
        // On initialise l'environnement Twig avec le loader
        $this->twig = new \Twig\Environment($this->loader, ['debug' => true]);
    }

    /**
     * @param mixed $data data sent to template engine (Twig in our case)
     * @param string $template name of the template to use
     * @return string the compiled template
     */
    public function render(string $template, mixed $data): string
    {
        // L'énoncé utilise render(data, template) mais le code utilise render(template, data).
        // On respecte l'implémentation actuelle de render() qui est render(template, data)
        return $this->twig->render($template, $data);
    }

    /**
     * Enregistre un nouveau chemin de template basé sur la classe View.
     * Exemple : Framework312\Router\View\BookView => ajoute le chemin templates/Book
     */
    public function register(string $tag): void
    {
        // Déduit le nom court de la classe (ex: BookView) qui sert de sous-dossier et d'alias (@BookView)
        $reflect = new \ReflectionClass($tag);
        $viewName = $reflect->getShortName();

        // Le chemin du dossier à ajouter est templatesFolder/NomDeLaView
        $tagPath = $this->templatesFolder . '/' . $viewName;

        // Ajoute le chemin et crée un alias Twig (@BookView) pour faciliter l'inclusion.
        // C'est la fonction addPath() du loader qui doit être utilisée.
        $this->loader->addPath($tagPath, $viewName);
    }
}
