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
}
