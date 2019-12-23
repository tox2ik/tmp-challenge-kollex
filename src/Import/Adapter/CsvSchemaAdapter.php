<?php

namespace kollex\Import\Adapter;

use kollex\DataProvider\Assortment\Product;
use kollex\Entity\ProductEntity;

class CsvSchemaAdapter implements SchemaAdapterInterface
{
    // maybe-todo: extract to abstract class; decide when we have more than 2-3 providers. 2019-12
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
        'product' => 'name',
        'manufacturer' => 'manufacturer',
        'id' => 'id',
        'ean' => 'gtin',

        'packaging product' => 'packaging',
        'packaging unit' => 'baseProductPackaging',
        'amount per unit' => 'baseProductAmount',
        //'stock' => 'baseProductQuantity',

        'description' => 'description',
    ];


    /** maybe-todo: get rid of the convert step, just return a product from decode() */
    public function convert(array $fields = null): Product
    {
        return new ProductEntity($fields);

    }

    /***
     * maybe-todo: extract common code in decode() to abstract adapter or trait
     * @param array|object $item
     * @return array
     */
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
        $baseAmount = strtolower($out['baseProductAmount']);
        if (strlen($baseAmount) && $baseAmount[-1] == 'l') {
            $out['baseProductAmount'] = trim($baseAmount, 'l');
        } elseif (strlen($baseAmount) && $baseAmount[-1] == 'g') {
            $out['baseProductAmount'] = trim($baseAmount, 'g');
            $out['baseProductUnit'] = 'GR';
        }

        if ($out['packaging'] === 'single') {
            $out['packaging'] = $out['baseProductPackaging'];
            $out['baseProductQuantity'] = 1;
        } if (false !== strpos($out['packaging'], ' ')) {
            $token = strtok($out['packaging'], ' ');
            while (false !== $token) {
                if (is_numeric($token)) {
                    $out['baseProductQuantity'] = $token;
                } else {
                    $out['packaging'] = $token; // possible pitfall if input unpredictable: e.q; 12 x case ; case @ 12
                    $out['packaging'] = static::$mapUnits[strtolower($out['packaging'])] ?? $out['packaging'];
                    $out['baseProductPackaging'] = static::$mapUnits[strtolower($out['baseProductPackaging'])]
                        ?? $out['baseProductPackaging'];
                }
                $token = strtok(' ');

            }
        }
        return $out;

    }
}
