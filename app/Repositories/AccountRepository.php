<?php

namespace App\Repositories;

use App\Models\Account;

interface AccountRepository
{
    /**
     * Método responsável por retornar os dados da conta
     */
    public function getAccount(int $accountId): ?Account;
}
