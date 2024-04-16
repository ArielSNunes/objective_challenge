<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddBalanceRequest;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Método responsável por retornar as informações da conta
     */
    public function index(Request $request)
    {
        // Busca a conta
        $account = $this->accountService->getAccountData(
            $request->query('id', null)
        );

        // Caso não exista, retorna 404
        if (!$account) {
            return response()->json(null, 404);
        }

        // Retorna a conta
        return response()->json([
            'conta_id' => $account->id,
            'saldo' => $account->balance
        ]);
    }

    /**
     * Método responsável por adicionar saldo à conta
     */
    public function addBalance(AddBalanceRequest $request)
    {
        // Captura o id da conta
        $accountId = $request->get('conta_id', null);

        // Adiciona saldo ou cria a conta para o id informado
        $account = $this->accountService->addBalanceOrCreateAccount(
            $accountId,
            $request->get('valor', null)
        );

        // Retorna a conta
        return response()->json([
            'conta_id' => $account->id,
            'saldo' => $account->balance
        ], 201);
    }
}
