<?php

namespace kollex\Entity;

use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Doctrine\ORM\Mapping as ORM;
use kollex\Dataprovider\Assortment\Product as ProviderProduct;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product implements ProviderProduct
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $dbid;
    /**
     * The wholesaler's unique product identifier
     *
     * Example:
     * ABC12345678
     *
     * @ORM\Column(type="string", unique=true)
     * @var string
     *
     *
     */
    protected $id;
    /**
     * The "Global Trade Identification Number" (GTIN/EAN), a public identifier for trade items, developed by GS1
     *
     * Example:
     * 05449000061704
     *
     * @ORM\Column(type="string", length=14)
     * @var string
     *
     *
     */
    protected $gtin;
    /**
     * Manufacturer name
     *
     * Example:
     * Beverages Ltd.
     *
     * @ORM\Column(type="string")
     * @var string
     *
     *
     */
    protected $manufacturer;
    /**
     * Product name
     *
     * Example:
     * Beverage 23, 6x 0.75 L
     *
     * @ORM\Column(type="string", nullable=false)
     * @var string
     *
     *
     */
    protected $name;
    /**
     * Packaging of the product (standardized units, see external docs), for example a case (CA)
     * Avaiable options:
     * - CA = case
     * - BX = box
     * - BO = bottle
     *
     * Example:
     * CA
     *
     * @ORM\Column(type="string", length=2, nullable=false)
     * @var string
     *
     * ENUM: CA, BX, BO
     */
    protected $packaging;
    /**
     * Packaging of the base product (standardized units, see external docs), for example a bottle (BO)
     * Avaiable options:
     * - BO = bottle
     * - CN = can
     *
     * Example:
     * BO
     *
     * @ORM\Column(type="string", length=2, nullable=false)
     * @var string
     *
     * ENUM: BO, CN
     */
    protected $baseProductPackaging;
    /**
     * Unit of measurement of the base product (standardized units, see external docs), for example liters (LT)
     * Avaiable options:
     * - LT = liters
     * - GR = grams
     *
     * Example:
     * LT
     *
     * @ORM\Column(type="string", nullable=false)
     * @var string
     *
     * ENUM: LT, GR
     */
    protected $baseProductUnit;
    /**
     * Amount of contents in the given unit of measurement of the base
     * product, for example 0.75 liters
     *
     * Example:
     * 0.75
     *
     * @ORM\Column(type="float")
     * @var float
     *
     *
     */
    protected $baseProductAmount;
    /**
     * Number of base products within the package, for example 6 bottles
     *
     * Example:
     * 6
     *
     * @ORM\Column(type="integer")
     * @var integer
     *
     *
     */
    protected $baseProductQuantity;

    public function __construct(array $properties = [])
    {
        foreach ($properties as $i => $e) {
            if (property_exists($this, $i)) {
                $this->{$i} = $e;
            }
        }
    }

    /**
     * @return array = [
     *     'property' => [ 'value' => '...', rules = [ ]],
     *     ...
     * ]
     */
    public function defineValidations(): array
    {
        $rules = [
            'id' => [
                'value' => null,
                'rules' => []
            ],
            'gtin' => [
                'value' => null,
                'rules' => [new Assert\Length(['max' => 14])]

            ],
            'manufacturer' => [
                'value' => null,
                'rules' => []

            ],
            'name' => [
                'value' => null,
                'rules' => []

            ],
            'packaging' => [
                'value' => null,
                'rules' => [new Assert\Choice(['choices' => ['CA', 'BX', 'BO']])]
            ],
            'baseProductPackaging' => [
                'value' => null,
                'rules' => [new Assert\Choice(['choices' => ['BO', 'CN']])]
            ],
            'baseProductUnit' => [
                'value' => null,
                'rules' => [new Assert\Choice(['choices' => ['LT', 'GR']])]

            ],
            'baseProductAmount' => [
                'value' => null,
                'rules' => [new Assert\PositiveOrZero()]

            ],
            'baseProductQuantity' => [
                'value' => null,
                'rules' => [new Assert\PositiveOrZero()]
            ],
        ];

        foreach (
            [
                'packaging',
                'baseProductPackaging'
            ] as $short
        ) {
            $rules[$short]['rules'][] = new Assert\Length(['min' => 2, 'max' => 2]);
        }

        foreach (
            [
                'id',
                'manufacturer',
                'name',
                'packaging',
                'baseProductPackaging',
                'baseProductUnit',
                'baseProductAmount',
                'baseProductQuantity',
            ] as $required
        ) {
            $rules[$required]['rules'][] = new Assert\NotBlank();
        }

        foreach ($rules as $i => &$def) {
            $def['value'] = $this->{$i};
        }
        return $rules;
    }

    public function __toString(): string
    {
        return "Product{{$this->name}, {$this->id}";
    }

    public function identify(): string
    {
        return $this->id;
    }
}
