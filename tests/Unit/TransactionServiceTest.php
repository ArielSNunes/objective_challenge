<?php

namespace Tests\Unit;

use App\Enums\PaymentMethod;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Services\AccountService;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
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

    public function test_if_can_register_a_transaction()
    {
        $account = $this->accountService->addBalanceOrCreateAccount(8932, 1000);
        $transaction = $this->transactionService->registerTransaction(
            PaymentMethod::PIX,
            $account->id,
            100
        );

        $this->assertNotNull($transaction);
    }
}
