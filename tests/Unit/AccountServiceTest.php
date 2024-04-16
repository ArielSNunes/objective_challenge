<?php

namespace Tests\Unit;

use App\Enums\PaymentMethod;
use App\Models\Account;
use App\Repositories\AccountRepository;
use App\Services\AccountService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    use RefreshDatabase;

    private AccountService $accountService;

    private Account $firstAccount;

    public function setUp(): void
    {
        parent::setUp();
        $accountRepository = app()->make(AccountRepository::class);
        $this->accountService = new AccountService($accountRepository);

        $firstAccount = new Account();
        $firstAccount->id = 1;
        $firstAccount->balance = 200;
        $firstAccount->save();

        $this->firstAccount = $firstAccount;
    }

    public function test_if_can_instantiate_service(): void
    {
        $this->assertNotEmpty($this->accountService);
    }

    public function test_if_can_add_balance_to_account()
    {
        $accountId = $this->firstAccount->id;
        $currentBalance = $this->firstAccount->balance;
        $newValue = 123.33;

        $this->accountService->addBalanceOrCreateAccount($accountId, $newValue);

        $account = $this->accountService->getAccountData($accountId);

        $this->assertEquals($currentBalance + $newValue, $account->balance);
    }

    public function test_if_can_create_account()
    {
        $account = $this->accountService->addBalanceOrCreateAccount(999, 123.33);

        $account = $this->accountService->getAccountData($account->id);

        $this->assertDatabaseHas('accounts', ['id' => 999]);
    }

    public function test_if_can_subtract_balance_to_account()
    {
        $accountId = $this->firstAccount->id;
        $currentBalance = $this->firstAccount->balance;
        $newValue = 123.33;

        $this->accountService->subBalance($accountId, $newValue);

        $account = $this->accountService->getAccountData($accountId);

        $this->assertEquals($currentBalance - $newValue, $account->balance);
    }

    public function test_if_can_get_tax_by_payment_type()
    {
        $this->assertEquals(
            $this->accountService->getTaxByPaymentType(PaymentMethod::PIX),
            1
        );

        $this->assertEquals(
            $this->accountService->getTaxByPaymentType(
                PaymentMethod::DEBIT_CARD
            ),
            1.03
        );

        $this->assertEquals(
            $this->accountService->getTaxByPaymentType(
                PaymentMethod::CREDIT_CARD
            ),
            1.05
        );
    }

    public function test_if_can_get_value_with_taxes()
    {
        $pixTax = $this->accountService->getTaxByPaymentType(
            PaymentMethod::PIX
        );
        $debitTax = $this->accountService->getTaxByPaymentType(
            PaymentMethod::DEBIT_CARD
        );
        $creditTax = $this->accountService->getTaxByPaymentType(
            PaymentMethod::CREDIT_CARD
        );

        $value = 33.3444;

        $this->assertEquals(
            $this->accountService->calculateValueWithTaxes($value, PaymentMethod::PIX),
            $value * $pixTax
        );
        $this->assertEquals(
            $this->accountService->calculateValueWithTaxes($value, PaymentMethod::DEBIT_CARD),
            $value * $debitTax
        );
        $this->assertEquals(
            $this->accountService->calculateValueWithTaxes($value, PaymentMethod::CREDIT_CARD),
            $value * $creditTax
        );
    }

    public function test_if_can_make_transaction_by_payment_type()
    {
        $value = 45;
        $canPayByPix = $this->accountService->checkIfAccountHasBalance(
            50,
            $this->accountService->calculateValueWithTaxes($value, PaymentMethod::PIX)
        );
        $canPayByDebit = $this->accountService->checkIfAccountHasBalance(
            50,
            $this->accountService->calculateValueWithTaxes($value, PaymentMethod::DEBIT_CARD)
        );
        $canPayByCredit = $this->accountService->checkIfAccountHasBalance(
            50,
            $this->accountService->calculateValueWithTaxes($value, PaymentMethod::CREDIT_CARD)
        );

        $this->assertTrue($canPayByPix);
        $this->assertTrue($canPayByDebit);
        $this->assertTrue($canPayByCredit);
    }

    public function test_if_cannot_make_transaction_by_payment_type()
    {
        $value = 51;
        $canPayByPix = $this->accountService->checkIfAccountHasBalance(
            50,
            $this->accountService->calculateValueWithTaxes($value, PaymentMethod::PIX)
        );
        $canPayByDebit = $this->accountService->checkIfAccountHasBalance(
            50,
            $this->accountService->calculateValueWithTaxes($value, PaymentMethod::DEBIT_CARD)
        );
        $canPayByCredit = $this->accountService->checkIfAccountHasBalance(
            50,
            $this->accountService->calculateValueWithTaxes($value, PaymentMethod::CREDIT_CARD)
        );

        $this->assertfalse($canPayByPix);
        $this->assertfalse($canPayByDebit);
        $this->assertfalse($canPayByCredit);
    }
}
