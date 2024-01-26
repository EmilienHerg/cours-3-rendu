<?php

namespace Tests;

use App\Entity\Person;
use App\Entity\Product;
use App\Entity\Wallet;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Faker\Generator;
use function PHPUnit\Framework\assertEquals;

class PersonTest extends TestCase
{
    private Generator $faker;
    private string $currency;
    private Wallet $wallet;
    private Person $person;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
        $this->name = $this->faker->name;
        $this->currency = 'EUR';
        $this->wallet = new Wallet($this->currency);
        $this->person = new Person($this->name, $this->currency);
    }

    public function testPersonConstruct(): void
    {
        $this->assertInstanceOf(Person::class, $this->person);
    }

    public function testGetName(): void
    {
        $this->assertEquals($this->person->getName(), $this->name);
    }

    public function testSetName(): void
    {
        $newName = $this->faker->name;
        $this->person->setName($newName);
        $this->assertEquals($this->person->getName(), $newName);
    }

    public function testGetWallet(): void
    {
        $this->assertEquals($this->person->getWallet(), $this->wallet);
    }

    public function testHasFundWithBalanceNull(): void
    {
        $this->assertEquals($this->person->hasFund(), false);
    }

    public function testHasFundWithBalanceNotNull(): void
    {
        $this->person->getWallet()->setBalance(5.0);
        $this->assertEquals($this->person->hasFund(), true);
    }

    public function testTransfertFundOK(): void
    {
        $this->person->getWallet()->setBalance(20.0);

        // initialisation d'une nouvelle personne
        $newPersonName = $this->faker->name;
        $newPersonCurrency = 'EUR';
        $newPerson = new Person($newPersonName, $newPersonCurrency);

        // test
        $this->person->transfertFund(13.0, $newPerson);
        $this->assertEquals($this->person->getWallet()->getBalance(), 7.0);
        $this->assertEquals($newPerson->getWallet()->getBalance(), 13.0);
    }

    public function testTransfertFundBadCurrency(): void
    {
        $this->person->getWallet()->setBalance(20.0);

        // initialisation d'une nouvelle personne
        $newPersonName = $this->faker->name;
        $newPersonCurrency = 'USD';
        $newPerson = new Person($newPersonName, $newPersonCurrency);

        // test
        $this->expectException(\Exception::class);
        $this->person->transfertFund(13.0, $newPerson);
    }

    public function testDivideWallet(): void
    {
        // Création d'une 2e personne
        $newName = $this->faker->name;
        $newPersonCurrency = 'USD';
        $newPerson = new Person($newName, $newPersonCurrency);

        // Création d'une 3e personne
        $newName2 = $this->faker->name;
        $newPersonCurrency2 = 'USD';
        $newPerson2 = new Person($newName2, $newPersonCurrency2);

        $this->person->wallet->addFund(15.0);
        $newPerson->wallet->addFund(13.0);
        $newPerson2->wallet->addFund(11.0);

        $arrayPeople = [$this->person, $newPerson, $newPerson2];

        $this->person->divideWallet($arrayPeople);

        $this->assertEquals($this->person->getWallet()->getBalance(), 15.0);
        $this->assertEquals($newPerson->getWallet()->getBalance(), 13.0);
        $this->assertEquals($newPerson2->getWallet()->getBalance(), 11.0);
    }

    public function testBuyProductOK(): void
    {
        // Création d'une 2e personne
        $newName = $this->faker->name;
        $newPersonCurrency = 'USD';
        $newPerson = new Person($newName, $newPersonCurrency);
        $newPerson->getWallet()->setBalance(20.0);

        $newPizza = new Product('Pizza', ['USD' => 11.0, 'EUR' => 10.0], 'food');
        $this->person->getWallet()->setBalance(15.0);

        // test de la méthode
        $this->person->buyProduct($newPizza);
        assertEquals($this->person->getWallet()->getBalance(), 5.0);

        $newPerson->buyProduct($newPizza);
        assertEquals($newPerson->getWallet()->getBalance(), 9.0);
    }

    public function testBuyProductBadCurrency(): void
    {
        $newPizza = new Product('Pizza', ['USD' => 11.0], 'food');
        $this->person->getWallet()->setBalance(15.0);
        $this->expectException(\Exception::class);
        $this->person->buyProduct($newPizza);
    }
}
