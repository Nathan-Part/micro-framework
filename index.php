<?php

declare(strict_types=1);

require_once __DIR__ . '\\vendor\\autoload.php';

use Framework312\Router\Request;
use Framework312\Template\TwigRenderer;
use Framework312\Router\SimpleRouter;
use Framework312\Router\View\TemplateView;
use Framework312\Router\View\HelloView;
use Framework312\Router\View\BookView;

class Hello extends TemplateView {
  public function template(Request $request): string
  {
    return 'index.twig';
  }

  public function get(Request $request): mixed
  {
    return [];
  }
}

class Book extends TemplateView {
  public function template(Request $request): string
  {
    return 'index.twig';
  }

  public function get(Request $request): mixed
  {
    $id = $request->attributes->get('id');
    return ['id' => $id];
  }
}

// 1. Créer un renderer
$renderer = new TwigRenderer(__DIR__ . '\\templates');

// 2. Créer le router
$router = new SimpleRouter($renderer);

// 3. Enregistrer les routes
$router->register('/json/hello', HelloView::class); // example with JSONView
$router->register('/twig/hello', Hello::class); // example with TwigView
$router->register('/html/book/:id', BookView::class); // example with HTMLView
$router->register('/twig/book/:id', Book::class); // example with TemplateView

// 4. Servir la requête
$router->serve();
