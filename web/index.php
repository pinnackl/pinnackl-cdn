<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Pinnackl\Controller', realpath(__DIR__.'/..').'/src');

$app = new Silex\Application();

$app['debug'] = true;

$toController = function($shortClass, $shortMethod)
{
    return sprintf('Pinnackl\Controller\%s::%sAction', $shortClass, $shortMethod);
};

$app->get('/', function () {
    return "<h1>Pinnackl - CDN</h1>";
})->bind('home');

$app->get('/api/images', $toController('Cdn', 'show'));

$app->match('/api/images/upload', $toController('Cdn', 'upload'));

$app->match('/api/avatars/upload', $toController('Cdn', 'avatar'));

$app->run();