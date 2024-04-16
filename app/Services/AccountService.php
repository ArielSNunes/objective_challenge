<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Models\Account;
use App\Repositories\AccountRepository;

class AccountService
{
    private AccountRepository $accountRepository;

    private $taxesByPaymentMethod = [
        PaymentMethod::PIX->value => 1,
        PaymentMethod::DEBIT_CARD->value => 1.03,
        PaymentMethod::CREDIT_CARD->value => 1.05
    ];

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

    /**
     * Método responsável por remover saldo da conta
     */
    public function subBalance(int $accountId, float $value): Account
    {
        return $this->accountRepository->subBalance($accountId, $value);
    }

    /**
     * Método responsável por capturar a taxa de pagamento de acordo com o tipo
     */
    public function getTaxByPaymentType(PaymentMethod $paymentMethod): float
    {
        return $this->taxesByPaymentMethod[$paymentMethod->value] ?? 1;
    }

    /**
     * Método responsável por calcular o valor com as taxas do tipo de pagamento
     */
    public function calculateValueWithTaxes(
        float $transactionValue,
        PaymentMethod $paymentMethod
    ): float {
        return $transactionValue * $this->getTaxByPaymentType($paymentMethod);
    }

    /**
     * Método responsável por validar se a conta tem saldo para realizar a
     * operação
     */
    public function checkIfAccountHasBalance(
        float $accountBalance,
        float $transactionValueWithTaxes
    ): bool {
        return $accountBalance >= $transactionValueWithTaxes;
    }
}
