<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{

    public function index(): JsonResponse
    {
        $stores = Transaction::groupedByStore();

        return response()->json([
            'success' => true,
            'stores' => $stores
        ]);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required']
        ]);

        $file = $request->file('file');
        Transaction::import($file->getPathname());

        return response()->json([
            'success' => true,
            'message' => 'Arquivo CNAB importado com sucesso'
        ]);
    }

}
