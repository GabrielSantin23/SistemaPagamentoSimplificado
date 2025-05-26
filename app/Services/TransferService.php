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
                if (!$payerWallet->debit($amount)) {
                    throw new Exception('Falha ao debitar valor da carteira do pagador.');
                }

                $authorizationResponse = $this->requestAuthorization();
                
                if (!$authorizationResponse['authorized']) {
                    $payerWallet->credit($amount);
                    $transaction->markAsFailed();
                    
                    return [
                        'success' => false,
                        'message' => 'Transação não autorizada pelo serviço externo: ' . 
                                    ($authorizationResponse['message'] ?? 'Motivo não especificado.')
                    ];
                }

                if (!$payeeWallet->credit($amount)) {
                    $payerWallet->credit($amount);
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
            $response = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['message']) && $data['message'] === 'Autorizado') {
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
            $response = Http::post('https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04', [
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
