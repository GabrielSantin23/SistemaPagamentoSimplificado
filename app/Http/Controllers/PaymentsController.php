<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Services\TransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentsController extends Controller
{
    protected $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function index()
    {
        $user = Auth::user();
        $wallet = $user->createWalletIfNotExists();
        
        $sentTransactions = $user->sentTransactions()
                                ->with('payee')
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
                                
        $receivedTransactions = $user->receivedTransactions()
                                    ->with('payer')
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

        return view('payments.index', [
            'user' => $user,
            'wallet' => $wallet,
            'sentTransactions' => $sentTransactions,
            'receivedTransactions' => $receivedTransactions
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user->canSendMoney()) {
            return redirect()->route('pagamento.index')
                            ->with('error', 'Lojistas não podem enviar dinheiro, apenas receber.');
        }
        
        $wallet = $user->createWalletIfNotExists();
        
        return view('payments.create', compact('user', 'wallet'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->canSendMoney()) {
            return redirect()->route('pagamento.index')
                            ->with('error', 'Lojistas não podem enviar dinheiro, apenas receber.');
        }
        
        $validator = Validator::make($request->all(), [
            'cpf_cnpj' => 'required|string|max:20',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('pagamento.create')
                            ->withErrors($validator)
                            ->withInput();
        }
        
        $payee = User::where('cpf_cnpj', $request->cpf_cnpj)->first();
        
        if (!$payee) {
            return redirect()->route('pagamento.create')
                            ->with('error', 'Destinatário não encontrado. Verifique o CPF/CNPJ informado.')
                            ->withInput();
        }
        
        if ($payee->id === $user->id) {
            return redirect()->route('pagamento.create')
                            ->with('error', 'Não é possível transferir para você mesmo.')
                            ->withInput();
        }
        
        $result = $this->transferService->transfer(
            $user,
            $payee,
            (float) $request->amount,
            $request->description
        );
        
        if ($result['success']) {
            return redirect()->route('pagamento.index')
                            ->with('success', $result['message']);
        } else {
            return redirect()->route('pagamento.create')
                            ->with('error', $result['message'])
                            ->withInput();
        }
    }

    public function history()
    {
        $user = Auth::user();
        
        $sentTransactions = $user->sentTransactions()
                                ->with('payee')
                                ->orderBy('created_at', 'desc')
                                ->get();
                                
        $receivedTransactions = $user->receivedTransactions()
                                    ->with('payer')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        
        return view('payments.history', compact('user', 'sentTransactions', 'receivedTransactions'));
    }

    public function show(Transaction $transaction)
    {
        $user = Auth::user();
        
        if ($transaction->payer_id !== $user->id && $transaction->payee_id !== $user->id) {
            return redirect()->route('pagamento.index')
                            ->with('error', 'Você não tem permissão para visualizar esta transação.');
        }
        
        return view('payments.show', compact('transaction'));
    }
}
