<?php

use DI\Container;
use DI\ContainerBuilder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;

require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder=new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../src/definations.php');
$container=$containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

require_once __DIR__ . '/../routes/web.php';

$app->run();
