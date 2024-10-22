<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Pages;
use App\Controllers\News;
use App\Controllers\Products;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('news/new', [News::class, 'new']);
$routes->post('news', [News::class, 'create']);

$routes->get('news',[News::class,'index']);
$routes->get('news/(:segment)',[News::class,'show']);


$routes->get('pages', [Pages::class,'index']);
$routes->get('(:segment)',[Pages::class,'view']);


// $routes->post('products',[Products::class,'creates']);

$routes->group('api', function($routes) {
    
    $routes->patch('products/(:segment)', 'Products::updateProduct/$1');
    $routes->resource('products');
});




