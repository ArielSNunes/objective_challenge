<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Models\Account;
use App\Repositories\AccountRepository;

class AccountService
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * Método responsável por retornar os dados da conta
     */
    public function getAccountData(?int $accountId): ?Account
    {
        // Caso não seja informado o id, retorna null
        if (!$accountId) {
            return null;
        }

        // Retorna a busca da conta no repository
        return $this->accountRepository->getAccount($accountId);
    }

    /**
     * Método responsável por criar ou adicionar saldo numa conta
     */
    public function addBalanceOrCreateAccount(
        int $accountId,
        float $value
    ): Account {
        // Busca a conta
        $account = $this->getAccountData($accountId);

        // Caso não exista, cria com o valor informado
        if (!$account) {
            return $this->accountRepository->createAccount($accountId, $value);
        }

        // Adiciona o saldo na conta
        return $this->accountRepository->addBalance($accountId, $value);
    }
}
