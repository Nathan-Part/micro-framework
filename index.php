<?php

// cette page juste un exemple afin de comprendre mieux les differents concepte a comprendre

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Framework312\Router\SimpleRouter;
use Framework312\Template\DummyRenderer;
use Framework312\Router\View\HelloView;
use Framework312\Router\View\BookView;

// 1. Créer un renderer fictif
$renderer = new DummyRenderer();

// 2. Créer le router
$router = new SimpleRouter($renderer);

// 3. Enregistrer les routes
$router->register('/hello', HelloView::class);
$router->register('/book/:id', BookView::class);

// 4. Servir la requête
$router->serve();
