<?php

namespace Tests;

use App\Entity\Person;
use App\Entity\Wallet;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Faker\Generator;

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

    public function testTrasnfertFundOK(): void {
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
}
