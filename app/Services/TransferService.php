<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TransferService
{
    public function transfer(User $payer, User $payee, float $amount, ?string $description = null): array
    {
        if (!$payer->canSendMoney()) {
            return [
                'success' => false,
                'message' => 'Lojistas não podem enviar dinheiro, apenas receber.'
            ];
        }

        if ($amount <= 0) {
            return [
                'success' => false,
                'message' => 'O valor da transferência deve ser maior que zero.'
            ];
        }

        $payerWallet = $payer->createWalletIfNotExists();
        $payeeWallet = $payee->createWalletIfNotExists();

        if ($amount > $payerWallet) {
            return [
                'success' => false,
                'message' => 'Saldo insuficiente para realizar a transferência.'
            ];
        }

        $transaction = Transaction::create([
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'amount' => $amount,
            'status' => 'pending',
            'description' => $description,
        ]);

        try {
            $result = DB::transaction(function () use ($payerWallet, $payeeWallet, $amount, $transaction, $payer, $payee) {
                if (!$payer->debit($amount)) {
                    throw new Exception('Falha ao debitar valor da carteira do pagador.');
                }

                $authorizationResponse = $this->requestAuthorization();
                
                if (!$authorizationResponse['authorized']) {
                    $payer->credit($amount);
                    $transaction->markAsFailed();
                    
                    return [
                        'success' => false,
                        'message' => 'Transação não autorizada pelo serviço externo: ' . 
                                    ($authorizationResponse['message'] ?? 'Motivo não especificado.')
                    ];
                }

                if (!$payee->credit($amount)) {
                    $payer->credit($amount);
                    $transaction->markAsFailed();
                    
                    throw new Exception('Falha ao creditar valor na carteira do recebedor.');
                }

                $transaction->markAsCompleted($authorizationResponse['authorization_code'] ?? null);

                $this->sendNotification($payer, $transaction, 'payer');
                $this->sendNotification($payee, $transaction, 'payee');

                return [
                    'success' => true,
                    'message' => 'Transferência realizada com sucesso!',
                    'transaction_id' => $transaction->id
                ];
            });

            return $result;

        } catch (Exception $e) {
            if ($transaction->status === 'pending') {
                $transaction->markAsFailed();
            }
            
            return [
                'success' => false,
                'message' => 'Erro ao processar a transferência: ' . $e->getMessage()
            ];
        }
    }

    private function requestAuthorization(): array
    {
        try {
            $response = Http::get('https://66ad1f3cb18f3614e3b478f5.mockapi.io/v1/auth');

            if ($response->successful()) {
                $data = $response->json();

                if (is_array($data)) {
                    foreach ($data as $item) {
                        if (isset($item['message']) && $item['message'] === 'Autorizado') {
                            return [
                                'authorized' => true,
                                'message' => $item['message'],
                                'authorization_code' => Str::random(10)
                            ];
                        }
                    }
                } elseif (isset($data['message']) && $data['message'] === 'Autorizado') {
                    return [
                        'authorized' => true,
                        'message' => $data['message'],
                        'authorization_code' => Str::random(10)
                    ];
                }
            }

            return [
                'authorized' => false,
                'message' => 'Serviço autorizador recusou a transação ou retornou resposta inesperada.'
            ];

        } catch (Exception $e) {
            return [
                'authorized' => false,
                'message' => 'Falha na comunicação com o serviço autorizador: ' . $e->getMessage()
            ];
        }
    }

    private function sendNotification(User $user, Transaction $transaction, string $userType): bool
    {
        try {
            $response = Http::post('https://66ad1f3cb18f3614e3b478f5.mockapi.io/v1/send', [
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'type' => $userType === 'payer' ? 'debit' : 'credit'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['message']) && $data['message'] === 'Success') {
                    if ($userType === 'payer') {
                        $transaction->markPayerNotified();
                    } else {
                        $transaction->markPayeeNotified();
                    }
                    
                    return true;
                }
            }
            
            return false;
            
        } catch (Exception $e) {
            return false;
        }
    }
}
