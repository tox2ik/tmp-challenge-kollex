<?php

namespace kollex\Import;

use kollex\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProducTest extends TestCase
{
    public function testIdentify_New_EmptyString()
    {
        $this->assertEquals('', (new Product)->identify());
    }

    public function testDefineValidation_New_MustReferToProperties()
    {
        $allDefined = true;
        foreach (array_keys((new Product)->defineValidations()) as $propName) {
            $allDefined = $allDefined && property_exists(Product::class, $propName);
        }
        $this->assertTrue($allDefined);
    }

    public function testConstructor_New_PopulatesFields()
    {
        $product = new Product(['id' => 'Cake', 'name' => 'Brownie', 'manufacturer' => 'Strudel']);
        $this->assertEquals(['Cake', 'Brownie', 'Strudel'], [$product->id, $product->name, $product->manufacturer]);
    }

    public function testConstructor_New_SkipsUndefined()
    {
        $this->expectError();
        $product = new Product([ 'nonField' => 'nope' ]);
        $this->assertNotEquals('nope', $product->nonField);

    }
}

