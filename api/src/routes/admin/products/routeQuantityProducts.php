<?php

use tezlikv2\dao\ProductsQuantityDao;

$productsQuantityDao = new ProductsQuantityDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


//CANTIDAD DE PRODUCTOS GENERAL
$app->post('/quantityProductsGeneral', function (Request $request, Response $response, $args) use ($productsQuantityDao) {
    $dataProducts = $request->getParsedBody();

    //NÚMERO DE PRODUCTOS GENERALES
    $quantity = $productsQuantityDao->totalProducts();
    $resp = $quantity;

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});


//CANTIDAD DE PRODUCTOS POR EMPRESA
$app->post('/quantityProducts', function (Request $request, Response $response, $args) use ($productsQuantityDao) {
    $dataProducts = $request->getParsedBody();

    //NÚMERO DE PRODCUTOS POR EMPRESA
    $quantity = $productsQuantityDao->totalProductsByCompany($dataProducts['id_company']);
    $resp = $quantity;

    $response->getBody()->write(json_encode($resp));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});
