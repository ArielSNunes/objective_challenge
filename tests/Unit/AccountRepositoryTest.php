<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Repositories\AccountRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private AccountRepository $accountRepository;

    private Account $firstAccount;

    public function setUp(): void
    {
        parent::setUp();

        $this->accountRepository = app()->make(AccountRepository::class);

        $firstAccount = new Account();
        $firstAccount->id = 1;
        $firstAccount->balance = 200;
        $firstAccount->save();

        $this->firstAccount = $firstAccount;
    }

    public function test_if_repository_is_instantiated()
    {
        $this->assertNotNull($this->accountRepository);
    }

    public function test_if_can_get_account_data()
    {
        $this->assertNotNull($this->accountRepository->getAccount(1));
    }

    public function test_if_can_add_balance_to_account()
    {
        $accountId = $this->firstAccount->id;
        $currentBalance = $this->firstAccount->balance;
        $newValue = 123.33;

        $this->accountRepository->addBalance($accountId, $newValue);

        $account = $this->accountRepository->getAccount($accountId);

        $this->assertEquals($currentBalance + $newValue, $account->balance);
    }

    public function test_if_can_subtract_balance_to_account()
    {
        $accountId = $this->firstAccount->id;
        $currentBalance = $this->firstAccount->balance;
        $newValue = 123.33;

        $this->accountRepository->subBalance($accountId, $newValue);

        $account = $this->accountRepository->getAccount($accountId);

        $this->assertEquals($currentBalance - $newValue, $account->balance);
    }
}
