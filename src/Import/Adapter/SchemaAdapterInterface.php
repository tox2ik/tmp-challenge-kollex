<?php

namespace kollex\Import\Adapter;

use kollex\DataProvider\Assortment\Product;

/***
 * Transform an external product specification to our Product schema.
 *
 * @startuml
 *
 * actor "main()"
 * main -> SourceInterface : importAll()
 *
 * activate SourceInterface
 *
 * activate ReaderInterface
 * SourceInterface -> ReaderInterface : getAllItems()
 * ReaderInterface --> SourceInterface : items
 * destroy ReaderInterface
 *
 * group for each item
 *    SourceInterface -> SchemaAdapterInterface : decode(externalItem)
 *    activate SchemaAdapterInterface
 *    SchemaAdapterInterface --> SourceInterface : properties[]
 *    SourceInterface -> SchemaAdapterInterface : convert(properties)
 *    SchemaAdapterInterface --> SourceInterface : Product
 *    destroy SchemaAdapterInterface
 *
 * SourceInterface -> ValidatorInterface : validate(Product)
 * ValidatorInterface -> SourceInterface : errors
 * end
 *
 * SourceInterface -> main : products
 *
 * destroy SourceInterface
 *
 *
 * @enduml
 *
 * @package kollex\Import\Adapter
 */
interface SchemaAdapterInterface
{
    /**
     * @param object|array $item external record
     * @return array dictionary (hashmap) for Product constructor
     */
    public function decode($item): array;

    /***
     * @param array $item external item, decoded
     * @return Product initialized from a decoded, external item
     */
    public function convert(array $item = null): Product;
}
