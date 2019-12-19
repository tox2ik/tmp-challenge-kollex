<?php

namespace kollex\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Product
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
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="string")
     * @var string
     *
     * ENUM: LT, GR
     */
    protected $baseProductUnit;

    /**
     * Amount of contents in the given unit of measurement of the base product, for example 0.75 liters
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
}
