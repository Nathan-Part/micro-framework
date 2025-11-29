<?php
// cette page juste un exemple afin de comprendre mieux les differents concepte a comprendre

declare(strict_types=1);

namespace Framework312\Template;

class DummyRenderer implements Renderer
{
    public function render(mixed $data, string $template): string
    {

        return '';
    }


    public function register(string $tag): void
    {
        // rien pour l'instant
    }
}
