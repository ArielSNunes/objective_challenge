<?php

namespace App\Repositories;

use App\Models\Account;

interface AccountRepository
{
    /**
     * Método responsável por retornar os dados da conta
     */
    public function getAccount(int $accountId): ?Account;

    /**
     * Método responsável por inserir saldo na conta
     */
    public function addBalance(int $accountId, float $value): Account;

    /**
     * Método responsável por criar uma conta
     */
    public function createAccount(int $accountId, float $value): Account;
}
