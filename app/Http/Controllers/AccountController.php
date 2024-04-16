<?php

namespace App\Http\Controllers;

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
}
