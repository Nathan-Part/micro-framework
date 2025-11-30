<?php

declare(strict_types=1);

namespace Framework312\Router;

use Framework312\Router\Exception as RouterException;
use Framework312\Template\Renderer;
use Framework312\Template\HTMLView;
use Symfony\Component\HttpFoundation\Response;

class Route
{
    private const VIEW_CLASS = 'Framework312\Router\View\BaseView';
    private const VIEW_USE_TEMPLATE_FUNC = 'use_template';
    private const VIEW_RENDER_FUNC = 'render';

    private string $view;

    public function __construct(string|object $class_or_view)
    {
        $reflect = new \ReflectionClass($class_or_view);
        $view = $reflect->getName();
        if (!$reflect->isSubclassOf(self::VIEW_CLASS)) {
            throw new RouterException\InvalidViewImplementation($view);
        }
        $this->view = $view;
    }

    public function getView(): object
    {
        // $this->view contient le nom complet de la classe (ex: Framework312\Router\View\HelloView)
        $classname = $this->view;

        // On instancie simplement la vue (pour HTMLView / JSONView ça suffit)
        return new $classname();
    }

    public function call(Request $request, ?Renderer $engine): void // Response
    {
        // TODO
    }
}

class SimpleRouter implements Router
{
    private Renderer $engine;

    /** @var array<string, Route> */
    private array $routes = [];


    public function __construct(Renderer $engine)
    {
        $this->engine = $engine;
        // TODO
    }

    public function register(string $path, string|object $class_or_view)
    {
        $this->routes[$path] = new Route($class_or_view);
    }

    public function serve(mixed ...$args): void
    {
        // 1. Créer la Request
        $request = Request::createFromGlobals();
        
        // 2. Récupérer le chemin demandé
        $path = $request->getPathInfo();
        
        $matchedRoute = null;
        $params = [];
        
        // 3. Parcourir toutes les routes pour trouver un match (avec ou sans :param)
        foreach ($this->routes as $pattern => $route) {
            $params = $this->matchPath($pattern, $path);
            
            if ($params !== null) {
                $matchedRoute = $route;
                break;
            }
        }
        
        // 4. Si aucune route ne matche → 404 simple
        if ($matchedRoute === null) {
            echo "Route non trouvée";
            return;
        }
        
        // 5. Injecter les paramètres dans la Request (id, slug, etc.)
        foreach ($params as $name => $value) {
            $request->attributes->set($name, $value);
        }
        
        // 6. Récupérer la View via Route
        $view = $matchedRoute->getView();

        // 7. Appeler render() et envoyer la réponse
        $response = $view->render($request);
        $response->send();
    }


    private function matchPath(string $pattern, string $path): ?array
    {
        $patternParts = explode('/', trim($pattern, '/'));
        $pathParts = explode('/', trim($path, '/'));

        if (count($patternParts) !== count($pathParts)) {
            return null;
        }

        $params = [];

        foreach ($patternParts as $index => $part) {
            $value = $pathParts[$index];

            if (strlen($part) > 1 && $part[0] === ':') {
                $paramName = substr($part, 1);
                $params[$paramName] = $value;
                continue;
            }

            if ($part !== $value) {
                return null;
            }
        }

        return $params;
    }
}
