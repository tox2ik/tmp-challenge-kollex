<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/bootstrap/orm.php';

use AuronConsultingOSS\Logger\Console;
use kollex\Entity\ProductMapper;
use kollex\Entity\ProductRepository;
use kollex\Export\JsonExporter;
use kollex\Import\Adapter\CsvSchemaAdapter;
use kollex\Import\CsvFileReader;
use kollex\Import\JsonFileReader;
use kollex\Import\FileSource;
use kollex\Import\Adapter\JsonSchemaAdapter;
use kollex\Import\ProductValidator;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validation;



class main
{
    static function importProducts(LoggerInterface $logger)
    {
        $pvalid = new ProductValidator(Validation::createValidatorBuilder()->getValidator());

        $ja = new JsonSchemaAdapter();
        $ca = new CsvSchemaAdapter();
        $json = new FileSource($pvalid, $ja, new JsonFileReader('data/wholesaler_b.json', ['dataPath' => 'data']));
        $csv = new FileSource($pvalid, $ca, new CsvFileReader('data/wholesaler_a.csv', ['delimiter' => ';']));

        $productsCsv = $csv->importAll();
        $productsJson = $json->importAll();
        static::report($csv, $productsCsv, $logger);
        static::report($json, $productsJson, $logger);


        $repo = (new ProductRepository(new ProductMapper(initOrm(), $logger)));
        $repo->saveMany($productsCsv);
        $repo->saveMany($productsJson);
    }

    private static function report(FileSource $provider, array $products, LoggerInterface $logger)
    {
        if ($provider->isErroneous()) {
            $logger->error(sprintf("Report\n  %s", join("\n  ", $provider->generateReport())));
        } else {
            $logger->info(sprintf("read %d products;\n  %s", count($products), join("\n  ", $products)));
        }
    }

    static function displayProducts(LoggerInterface $logger)
    {


        $repo = new ProductRepository(new ProductMapper(initOrm()));
        $formatter = new JsonExporter();
        $items = $repo->findAll();
        echo "[" . PHP_EOL;


        foreach ($items as $i => $e) {
            echo $formatter->setItem($e)->serialize();
            if ($i+1 < count($items)) {
                print "," . PHP_EOL;
            }
        }

        echo  PHP_EOL . "]";

    }
}



$logger = new AuronConsultingOSS\Logger\Console(false);

if (count($argv) == 1) {
    main::importProducts($logger);
    main::displayProducts($logger);
} elseif (count($argv) >= 1) {
    $x = $argv[1];
    main::{$argv[1]}($logger);
}


