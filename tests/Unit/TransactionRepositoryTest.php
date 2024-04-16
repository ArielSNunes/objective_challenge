<?php

namespace Tests\Unit;

use App\Enums\PaymentMethod;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Services\AccountService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TransactionRepository $transactionRepository;

    private AccountService $accountService;
    public function setUp(): void
    {
        parent::setUp();
        $this->transactionRepository = app()->make(TransactionRepository::class);

        $accountRepository = app()->make(AccountRepository::class);
        $this->accountService = new AccountService($accountRepository);
    }

    public function test_if_can_save_a_transaction()
    {
        $account = $this->accountService->addBalanceOrCreateAccount(4455, 1000);

        $transaction = $this->transactionRepository->registerTransaction(
            PaymentMethod::PIX,
            $account->id,
            100
        );

        $this->assertNotNull($transaction);
    }
}
