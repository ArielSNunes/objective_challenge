<?php

namespace App\Repositories\Impl\Eloquent;

use App\Enums\PaymentMethod;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;

class TransactionRepositoryEloquent implements TransactionRepository
{
    public function registerTransaction(
        PaymentMethod $paymentMethod,
        int $accountId,
        float $value
    ): Transaction {
        $transaction = new Transaction();
        $transaction->account_id = $accountId;
        $transaction->value = $value;
        $transaction->payment_method = $paymentMethod;
        $transaction->save();
        return $transaction;
    }
}
