<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/bootstrap/orm.php';

use kollex\Entity\ProductMapper;
use kollex\Entity\ProductRepository;
use kollex\Import\Adapter\CsvSchemaAdapter;
use kollex\Import\JsonFileReader;
use kollex\Import\FileSource;
use kollex\Import\Adapter\JsonSchemaAdapter;
use kollex\Import\ProductValidator;
use Symfony\Component\Validator\Validation;


class main
{
    static function importProducts()
    {

        $logger = new AuronConsultingOSS\Logger\Console(false);

        $validator = Validation::createValidatorBuilder()
                               ->enableAnnotationMapping()
                               ->getValidator();

        $provider1 = new FileSource(
            new JsonFileReader('data/wholesaler_b.json', [ 'dataPath' => 'data' ]),
            new JsonSchemaAdapter(),
            new ProductValidator($validator)
        );

        $products = $provider1->importAll();

        $logger->info(sprintf("read %d products;\n  %s", count($products), join("\n  ", $products)));


        $productRepo = new ProductRepository(new ProductMapper(initOrm(), $logger));
        $productRepo->saveMany($provider1->importAll());



        if ($provider1->isErroneous()) {
            printf("Report\n  %s", join("\n  ", $provider1->generateReport()));
        }

        // $entityManager->persist($product);
        // $entityManager->flush();



        //$provider2 = new FileSource(new FileReader('data/wholesaler_b.json'), new JsonSchemaAdapter());
        // $productRepo->saveMany($provider2->importAll());
        // printf("persisted.\n");

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


