<?php

use tezlikv2\dao\ProductsDao;

$productsDao = new ProductsDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->post('/products', function (Request $request, Response $response, $args) use ($productsDao) {
    $products = $productsDao->findAllProductsByCompany('44');
    $response->getBody()->write(json_encode($products, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
