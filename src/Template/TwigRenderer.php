<?php

declare(strict_types=1);

namespace Framework312\Template;

class TwigRenderer implements Renderer
{
    private string $templatesFolder;
    private \Twig\Environment $twig;

    public function __construct(string $templatesFolder)
	{
        $this->templatesFolder = $templatesFolder;

		$loader = new \Twig\Loader\FilesystemLoader($templatesFolder);
		$this->twig = new \Twig\Environment($loader, ['debug' => true]);
	}

    /**
     * @param mixed $data data sent to template engine (Twig in our case)
     * @param string $template name of the template to use
     * @return string the compiled template
     */
    public function render(string $template, mixed $data): string
    {
        return $this->twig->render($template, $data);
    }

    public function register(string $tag): void
    {
        $loader = new \Twig\Loader\FilesystemLoader($this->templatesFolder . '\\' . $tag);
		$this->twig = new \Twig\Environment($loader, ['debug' => true]);
    }
}
