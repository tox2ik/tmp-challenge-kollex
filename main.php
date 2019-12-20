<?php

require_once __DIR__ . '/vendor/autoload.php';

use kollex\Entity\ProductRepository;
use kollex\Import\Adapter\CsvSchemaAdapter;
use kollex\Import\JsonFileReader;
use kollex\Import\FileSource;
use kollex\Import\Adapter\JsonSchemaAdapter;


class main
{
    static function importProducts()
    {
        $provider1 = new FileSource(
            new JsonFileReader('data/wholesaler_b.json', [ 'dataPath' => 'data' ]),
            new JsonSchemaAdapter()
        );


        $provider1->importAll();

        //$provider2 = new FileSource(new FileReader('data/wholesaler_b.json'), new JsonSchemaAdapter());
        // $productRepo = new ProductRepository(new ProductMapper());
        // $productRepo->saveMany($provider1->importAll());
        // $productRepo->saveMany($provider2->importAll());

        printf("imported.\n");
    }

    static function displayProducts()
    {
    }
}


if ($argv[1] ?? false) {


    main::{$argv[1]}();

} else {
    main::importProducts();
    main::displayProducts();
}


