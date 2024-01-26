<?php

namespace Tests;

use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    private string $currency;
    private Wallet $wallet;

    protected function setUp(): void
    {
        $this->currency = 'EUR';
        $this->wallet = new Wallet($this->currency);
    }

    public function testWalletConstruct(): void
    {
        $this->assertInstanceOf(Wallet::class, $this->wallet);
    }

    public function testGetBalance(): void
    {
        $this->assertEquals($this->wallet->getBalance(), 0.0);
    }

    public function testGetCurrency(): void
    {
        $this->assertEquals($this->wallet->getCurrency(), $this->currency);
    }

    public function testSetBalanceOK(): void
    {
        $this->wallet->setBalance(17.8);
        $this->assertEquals($this->wallet->getBalance(), 17.8);
    }

    public function testSetBalanceNegative(): void
    {
        $this->expectException(\Exception::class);
        $this->wallet->setBalance(-3.2);
    }

    public function testSetCurrencyOK(): void
    {
        $this->wallet->setCurrency('USD');
        $this->assertEquals($this->wallet->getCurrency(), 'USD');
    }

    public function testSetCurrencyUnknownCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->wallet->setCurrency('IOFHEI');
    }

    public function testRemoveFundOK(): void
    {
        $this->wallet->setBalance(20.0);
        $this->wallet->removeFund(13.0);
        $this->assertEquals($this->wallet->getBalance(), 7.0);
    }
    public function testRemoveFundNegativeAmount(): void
    {
        $this->wallet->setBalance(20.0);
        $this->expectException(\Exception::class);
        $this->wallet->removeFund(-5.0);
    }

    public function testRemoveFundInsuffisantFunds(): void
    {
        $this->wallet->setBalance(20.0);
        $this->expectException(\Exception::class);
        $this->wallet->removeFund(25.0);
    }

    public function testAddFundOK(): void
    {
        $this->wallet->setBalance(20.0);
        $this->wallet->addFund(17.0);
        $this->assertEquals($this->wallet->getBalance(), 37.0);
    }

    public function testAddFundNegativeAmount(): void
    {
        $this->wallet->setBalance(20.0);
        $this->expectException(\Exception::class);
        $this->wallet->addFund(-5.0);
    }
}
