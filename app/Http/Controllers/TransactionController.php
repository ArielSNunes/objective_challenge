<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Http\Requests\MakeTransactionRequest;
use App\Services\AccountService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private TransactionService $transactionService;
    private AccountService $accountService;

    public function __construct(
        TransactionService $transactionService,
        AccountService $accountService
    ) {
        $this->transactionService = $transactionService;
        $this->accountService = $accountService;
    }
    public function makeTransaction(MakeTransactionRequest $request)
    {
        $accountId = $request->get('conta_id', null);

        // Busca a conta
        $account = $this->accountService->getAccountData($accountId);

        if (!$account) {
            return response()->json(null, 404);
        }

        // Captura o valor e forma de pagamento
        $value = $request->get('valor', 0);
        $paymentType = PaymentMethod::tryFrom($request->get('forma_pagamento'));

        // Calcula o valor com taxa da forma de pagamento
        $valueWithTaxes = $this->accountService->calculateValueWithTaxes(
            $value,
            $paymentType
        );

        // Verifica se pode fazer a transação
        $canMakeTransaction = $this->accountService->checkIfAccountHasBalance(
            $account->balance,
            $valueWithTaxes,
            $paymentType
        );

        if (!$canMakeTransaction) {
            return response()->json(null, 404);
        }

        // Registra a transação
        $registerTransaction = $this->transactionService->registerTransaction(
            $paymentType,
            $accountId,
            $valueWithTaxes
        );

        if (!$registerTransaction) {
            return response()->json(null, 404);
        }

        // Salva o novo saldo e retorna o valor novo
        $account = $this->accountService->subBalance(
            $accountId,
            $valueWithTaxes
        );

        return response()->json([
            'conta_id' => $account->id,
            'saldo' => $account->balance
        ], 201);
    }
}
