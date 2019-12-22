<?php

namespace kollex\Import;

use kollex\Entity\ProductEntity;
use PHPUnit\Framework\TestCase;

/***
 * @covers \kollex\Entity\ProductEntity
 * @covers ProviderProduct
 * @package kollex\Import
 */
class ProducTest extends TestCase
{
    public function testIdentify_New_EmptyString()
    {
        $this->assertEquals('', (new ProductEntity)->identify());
    }

    public function testDefineValidation_New_MustReferToProperties()
    {
        $allDefined = true;
        foreach (array_keys((new ProductEntity)->defineValidations()) as $propName) {
            $allDefined = $allDefined && property_exists(ProductEntity::class, $propName);
        }
        $this->assertTrue($allDefined);
    }

    public function testConstructor_New_PopulatesFields()
    {
        $product = new ProductEntity(['id' => 'Cake', 'name' => 'Brownie', 'manufacturer' => 'Strudel']);
        $this->assertEquals(['Cake', 'Brownie', 'Strudel'], [$product->id, $product->name, $product->manufacturer]);
    }

    public function testConstructor_New_SkipsUndefined()
    {
        $this->expectError();
        $product = new ProductEntity(['nonField' => 'nope' ]);
        $this->assertNotEquals('nope', $product->nonField);
    }
}

