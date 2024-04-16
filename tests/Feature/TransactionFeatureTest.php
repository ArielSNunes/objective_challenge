<?php

namespace Tests\Feature;

use App\Enums\PaymentMethod;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Services\AccountService;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionFeatureTest extends TestCase
{
    use RefreshDatabase;

    private TransactionService $transactionService;
    private AccountService $accountService;

    public function setUp(): void
    {
        parent::setUp();

        $transactionRepository = app()->make(TransactionRepository::class);
        $this->transactionService = new TransactionService(
            $transactionRepository
        );

        $accountRepository = app()->make(AccountRepository::class);
        $this->accountService = new AccountService($accountRepository);
    }

    public function test_if_challenge_example_is_reached()
    {
        // Variáveis para os cálculos
        $accountId = rand(1, 9999);
        $initialBalance = 500;

        /**
         * Item 1: Valida se a conta existe para o id
         */
        $this->assertDatabaseMissing('accounts', ['id' => $accountId]);

        /**
         * Item 2: Cria a conta com saldo inicial de R$ 500
         */
        $account = $this->accountService->addBalanceOrCreateAccount(
            $accountId,
            $initialBalance
        );

        /**
         * Item 3: Consulta o saldo dela
         */
        $account = $this->accountService->getAccountData($accountId);

        /**
         * Item 4: Efetua uma compra de R$ 50 no débito
         */
        $debitValue = $this->accountService->calculateValueWithTaxes(
            50,
            PaymentMethod::DEBIT_CARD
        );
        $this->transactionService->registerTransaction(
            PaymentMethod::DEBIT_CARD,
            $accountId,
            $debitValue
        );
        $this->accountService->subBalance($accountId, $debitValue);

        /**
         * Item 5: Execute uma compra de R$ 100 no crédito
         */
        $creditValue = $this->accountService->calculateValueWithTaxes(
            100,
            PaymentMethod::CREDIT_CARD
        );
        $this->transactionService->registerTransaction(
            PaymentMethod::CREDIT_CARD,
            $accountId,
            $creditValue
        );
        $this->accountService->subBalance($accountId, $creditValue);

        /**
         * Item 6: Realize um PIX de R$ 75
         */
        $pixValue = $this->accountService->calculateValueWithTaxes(
            75,
            PaymentMethod::PIX
        );
        $this->transactionService->registerTransaction(
            PaymentMethod::PIX,
            $accountId,
            $pixValue
        );
        $this->accountService->subBalance($accountId, $pixValue);

        // Captura o novo saldo da conta
        $account = $this->accountService->getAccountData($accountId);

        // Calcula manualmente as taxas informadas, para o novo saldo
        $finalBalance = $initialBalance - (
            (50 * 1.03) + (100 * 1.05) + (75 * 1)
        );

        // Valida se foram gravadas as transações
        $this->assertDatabaseHas('transactions', [
            'account_id' => $accountId,
            'value' => $debitValue,
            'payment_method' => PaymentMethod::DEBIT_CARD
        ]);
        $this->assertDatabaseHas('transactions', [
            'account_id' => $accountId,
            'value' => $creditValue,
            'payment_method' => PaymentMethod::CREDIT_CARD
        ]);
        $this->assertDatabaseHas('transactions', [
            'account_id' => $accountId,
            'value' => $pixValue,
            'payment_method' => PaymentMethod::PIX
        ]);

        // Valida se o saldo na base é o mesmo calculado
        $this->assertEquals($account->balance, $finalBalance);
    }
}
