<?php

namespace App\Repositories\Impl\Eloquent;

use App\Models\Account;
use App\Repositories\AccountRepository;

class AccountRepositoryEloquent implements AccountRepository
{
    public function getAccount(int $accountId): ?Account
    {
        return Account::find($accountId);
    }

    public function addBalance(int $accountId, float $value): Account
    {
        $account = $this->getAccount($accountId);
        $account->balance = $account->balance + $value;
        $account->save();
        return $account;
    }

    public function createAccount(int $accountId, float $value): Account
    {
        $account = new Account();
        $account->id = $accountId;
        $account->balance = $value;
        $account->save();

        return $account;
    }

    public function subBalance(int $accountId, float $value): Account
    {
        $account = $this->getAccount($accountId);
        $account->balance = $account->balance - $value;
        $account->save();
        return $account;
    }
}
