<?php

use tezlikv2\dao\PricesDao;

$pricesDao = new PricesDao();

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Consulta todos */

$app->get('/prices', function (Request $request, Response $response, $args) use ($pricesDao) {
    $prices = $pricesDao->findAllPricesByCompany();
    $response->getBody()->write(json_encode($prices, JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json');
});
