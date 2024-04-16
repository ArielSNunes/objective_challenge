<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;

class TransactionService
{
    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Método responsável por registrar a transação
     */
    public function registerTransaction(
        PaymentMethod $paymentMethod,
        int $accountId,
        float $value
    ): Transaction {
        return $this->transactionRepository->registerTransaction(
            $paymentMethod,
            $accountId,
            $value
        );
    }
}
