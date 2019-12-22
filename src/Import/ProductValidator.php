<?php

namespace kollex\Import;

use kollex\DataProvider\Assortment\Product;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductValidator
{
    protected $validator;

    public function __construct(ValidatorInterface $validator = null)
    {
        $this->validator = $validator ?: Validation::createValidator();
    }

    /** @return string[] error messages, if any */
    public function validate(Product $product): array
    {
        $errors = [];
        foreach ($product->defineValidations() as $propertyName => $definition) {
            $violations = $this->validator->validate($definition['value'], $definition['rules']);
            /** @var ConstraintViolation $e */
            foreach ($violations as $e) {
                $identity = trim(sprintf("%s; ", $product->identify()));
                $identity = $identity == ';' ? '' : "id=$identity ";
                $errors[] = sprintf("%s%s (%s:%s)", $identity, $e->getMessage(), $propertyName, $definition['value']);
            }
        }
        return $errors;
    }
}
