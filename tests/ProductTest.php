<?php

namespace Tests;

use App\Entity\Product;
use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    private string $name;
    private array $prices;
    private string $type;
    private Product $product;

    protected function setUp(): void
    {
        $this->name = "Laptop";
        $this->prices = ['USD' => 543.0, 'EUR' => 500.0];
        $this->type = 'tech';
        $this->product = new Product($this->name, $this->prices, $this->type);
    }

    public function testProductConstruct(): void
    {
        $this->assertInstanceOf(Product::class, $this->product);
    }

    public function testGetName(): void
    {
        $this->assertEquals($this->product->getName(), $this->name);
    }

    public function testGetPrices(): void
    {
        $this->assertEquals($this->product->getPrices(), $this->prices);
    }

    public function testGetType(): void
    {
        $this->assertEquals($this->product->getType(), $this->type);
    }

    public function testSetTypeOK(): void
    {
        $this->product->setType('food');
        $this->assertEquals($this->product->getType(), 'food');
    }

    public function testSetTypeUnknownType(): void
    {
        $this->expectException(\Exception::class);
        $this->product->setType('drink');
    }

    public function testSetPricesOK(): void
    {
        // initialisation d'un nouveau produit
        $newPrices = ['EUR' => 300.0];
        $newLaptop = new Product($this->name, $newPrices, $this->type);

        $newLaptop->setPrices(['USD' => 326.0]);
        $this->assertEquals($newLaptop->getPrices(), ['EUR' => 300.0, 'USD' => 326.0]);
    }

    public function testSetPricesNegativePrice(): void
    {
        // initialisation d'un nouveau produit
        $newPrices = ['EUR' => 300.0];
        $newLaptop = new Product($this->name, $newPrices, $this->type);

        // test de la méthode
        $newLaptop->setPrices(['USD' => -5.0]);
        $this->assertEquals($newLaptop->getPrices(), ['EUR' => 300.0]);
    }

    public function testSetPricesUnknownCurrency(): void
    {
        // initialisation d'un nouveau produit
        $newPrices = ['EUR' => 300.0];
        $newLaptop = new Product($this->name, $newPrices, $this->type);

        // test de la méthode
        $newLaptop->setPrices(['CAD' => 438]);
        $this->assertEquals($newLaptop->getPrices(), ['EUR' => 300.0]);
    }

    public function testSetName(): void
    {
        $this->product->setName('Ordinateur');
        $this->assertEquals($this->product->getName(), 'Ordinateur');
    }

    public function testListCurrencies(): void
    {
        $this->assertEquals($this->product->listCurrencies(), ['USD', 'EUR']);
    }

    public function testGetPriceOK(): void
    {
        $this->assertEquals($this->product->getPrice('USD'), 543.0);
        $this->assertEquals($this->product->getPrice('EUR'), 500.0);
    }

    public function testGetPriceUnknownCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->product->getPrice('CAD');
    }

    public function testGetPriceNoCurrency(): void
    {
        // initialisation d'un nouveau produit
        $newPrices = ['EUR' => 300.0];
        $newLaptop = new Product($this->name, $newPrices, $this->type);

        // test de la méthode
        $this->expectException(\Exception::class);
        $newLaptop->getPrice('USD');
    }
}
