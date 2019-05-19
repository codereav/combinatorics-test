<?php ini_set('max_execution_time',360);

use FastRoute\RouteCollector;

require __DIR__ . '/../vendor/autoload.php';

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(
    [

        \Symfony\Component\HttpFoundation\Request::class => function () {
            return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        },
        // Bind an interface to an implementation
        \App\Models\PermModelInterface::class => \DI\autowire(\App\Models\PermModel::class)

    ]
);

$container = $builder->build();

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\Controllers\MainController', 'index']);
    $r->addRoute(['GET', 'POST'], '/build', ['App\Controllers\MainController', 'buildPerms']);

});


$route = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
switch ($route[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $controller = $route[1];
        $parameters = $route[2];

        $container->call($controller, $parameters);
        break;
}