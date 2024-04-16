<?php

namespace App\Repositories;

use App\Enums\PaymentMethod;
use App\Models\Transaction;

interface TransactionRepository
{
    /**
     * Método responsável por registrar uma transação
     */
    public function registerTransaction(
        PaymentMethod $paymentMethod,
        int $accountId,
        float $value
    ): Transaction;
}
