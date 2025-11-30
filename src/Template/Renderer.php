<?php

declare(strict_types=1);

namespace Framework312\Template;

interface Renderer
{
    public function render(string $template, mixed $data): string;
    public function register(string $tag);
}
