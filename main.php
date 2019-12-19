<?php

require_once __DIR__ . '/vendor/autoload.php';

use kollex\Entity\ProductRepository;
use kollex\Import\Adapter\CsvSchemaAdapter;
use kollex\Import\FileReader;
use kollex\Import\FileSource;
use kollex\Import\Adapter\JsonSchemaAdapter;


function importProducts()
{
    $provider1 = new FileSource(new FileReader('data/wholesaler_a.csv'), new CsvSchemaAdapter());
    $provider2 = new FileSource(new FileReader('data/wholesaler_b.json'), new JsonSchemaAdapter());

    $productRepo = new ProductRepository(new ProductMapper());
    $productRepo->saveMany($provider1->importAll());
    $productRepo->saveMany($provider2->importAll());
}

function displayProducts()
{
}


importProducts();
displayProducts();
