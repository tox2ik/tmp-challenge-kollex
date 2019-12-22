<?php

namespace kollex\Import;

use kollex\Entity\ProductEntity as Product;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductValidatorTest extends TestCase
{
    public function testDefaultRules()
    {
        $errors = (new ProductValidator())->validate(new Product);
        $this->assertNotEmpty($errors);
    }

    public function testRequiredFields()
    {
        $this->assertEquals(
            [

                'This value should not be blank. (id:)',
                'This value should not be blank. (manufacturer:)',
                'This value should not be blank. (name:)',
                'This value should not be blank. (packaging:)',
                'This value should not be blank. (baseProductPackaging:)',
                'This value should not be blank. (baseProductUnit:)',
                'This value should not be blank. (baseProductAmount:)',
                'This value should not be blank. (baseProductQuantity:)',
            ],
            (new ProductValidator())->validate(new Product)
        );
    }

    public function testValidate_PartiallyInitialized_ReportsIdentity()
    {
        $this->assertContains(
            'id=cake-xxl; This value should not be blank. (name:)',
            (new ProductValidator)->validate(new Product(['id' => 'cake-xxl'])));

    }

    public function testOverrideValidator()
    {
        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects($this->atLeastOnce())->method('validate')->willReturn([]);
        (new ProductValidator($validator))->validate(new Product);
    }
}
