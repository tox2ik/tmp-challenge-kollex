<?php

namespace kollex\Import\Adapter;

use kollex\DataProvider\Assortment\Product;
use kollex\Entity\ProductEntity as ProductEntity;

class JsonSchemaAdapter implements SchemaAdapterInterface
{

    protected static $mapUnits = [
        'bottle' => 'BO',
        'case' => 'CA',
        'box' => 'BX',
        'can' => 'CN',

        'CA' => 'CA',
        'BX' => 'BX',
        'BO' => 'BO',
        'CN' => 'CN',
        'LT' => 'LT',
        'GR' => 'GR'
    ];

    protected static $mapExternal = [
        'NAME' => 'name',
        'BRAND' => 'manufacturer',
        'PRODUCT_IDENTIFIER' => 'id',
        'EAN_CODE_GTIN' => 'gtin',

        'PACKAGE' => 'packaging',
        'VESSEL' => 'baseProductPackaging',
        'LITERS_PER_BOTTLE' => 'baseProductAmount',
        'BOTTLE_AMOUNT' => 'baseProductQuantity',

        'ADDITIONAL_INFO' => 'description',
    ];

    public function convert(array $properties = null): Product
    {
        return new ProductEntity($properties);
    }

    public function decode($item): array
    {
        $item = (array)$item;
        $out = ['baseProductUnit' => 'LT'];
        foreach (static::$mapExternal as $external => $name) {
            $out[$name] = $item[$external] ?? null;
        }
        ksort($out);

        $out['packaging'] = static::$mapUnits[strtolower($out['packaging'])] ?? $out['packaging'];
        $out['baseProductPackaging'] = static::$mapUnits[strtolower($out['baseProductPackaging'])]
            ?? $out['baseProductPackaging'];
        // --- common code above

        return $out;
    }
}
