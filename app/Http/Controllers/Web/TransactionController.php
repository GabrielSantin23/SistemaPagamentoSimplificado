<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    private const AUTHORIZATION_MOCK_URL = "https://util.devi.tools/api/v2/authorize";
    private const NOTIFICATION_MOCK_URL = "https://util.devi.tools/api/v1/notify";

    public function create()
    {
        return view('payments.index');
    }

    public function store(Request $request)
    {
        $payer = Auth::user();

        $validated = $request->validate([
            "payee_id" => ["required", "exists:users,id", function ($attribute, $value, $fail) use ($payer) {
                if ($value == $payer->id) {
                    $fail("Você não pode transferir dinheiro para si mesmo.");
                }
            }],
            "value" => ["required", "numeric", "min:0.01"],
        ]);

        $payee = User::findOrFail($validated["payee_id"]);
        $value = floatval($validated["value"]);

        if (!$payer->isComum()) {
            throw ValidationException::withMessages([
                "payer_id" => "Lojistas não podem realizar transferências, apenas receber."
            ]);
        }

        if ($payer->balance < $value) {
            throw ValidationException::withMessages([
                "value" => "Saldo insuficiente para realizar a transferência."
            ]);
        }

        $transaction = null;
        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                "payer_id" => $payer->id,
                "payee_id" => $payee->id,
                "value" => $value,
                "status" => "PENDING",
            ]);

            $authResponse = Http::get(self::AUTHORIZATION_MOCK_URL);

            if (!$authResponse->successful() || $authResponse->json("message") !== "Autorizado") {
                $transaction->update(["status" => "FAILED"]);
                DB::rollBack();
                Log::warning("Transferência não autorizada", ["transaction_id" => $transaction->id, "payer_id" => $payer->id, "payee_id" => $payee->id, "value" => $value]);
                throw ValidationException::withMessages([
                    "authorization" => "Transferência não autorizada pelo serviço externo."
                ]);
            }

            $transaction->update([
                "status" => "AUTHORIZED",
                "authorized_at" => now(),
            ]);

            $payerLocked = User::where("id", $payer->id)->lockForUpdate()->first();
            $payeeLocked = User::where("id", $payee->id)->lockForUpdate()->first();

            if ($payerLocked->balance < $value) {
                 DB::rollBack();
                 $transaction->update(["status" => "FAILED"]);
                 throw ValidationException::withMessages([
                     "value" => "Saldo insuficiente após bloqueio para atualização."
                 ]);
            }

            $payerLocked->wallet -= $value;
            $payeeLocked->wallet += $value;
            $payerLocked->save();
            $payeeLocked->save();

            $transaction->update([
                "status" => "COMPLETED",
                "completed_at" => now(),
            ]);

            DB::commit();

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            if ($transaction && $transaction->status !== "COMPLETED") {
                $transaction->status = "FAILED";
                $transaction->saveQuietly();
            }
            Log::error("Erro durante a transferência", ["exception" => $e, "transaction_id" => $transaction->id ?? null]);
            throw ValidationException::withMessages([
                "transfer" => "Ocorreu um erro inesperado durante a transferência. Tente novamente mais tarde."
            ]);
        }

        try {
            $notificationPayloadPayer = [
                "email" => $payer->email,
                "message" => "Sua transferência de R$ " . number_format($value, 2, ",", ".") . " para {$payee->name} foi realizada com sucesso."
            ];
            $notificationPayloadPayee = [
                "email" => $payee->email,
                "message" => "Você recebeu uma transferência de R$ " . number_format($value, 2, ",", ".") . " de {$payer->name}."
            ];

            $responsePayer = Http::post(self::NOTIFICATION_MOCK_URL, $notificationPayloadPayer);
            $responsePayee = Http::post(self::NOTIFICATION_MOCK_URL, $notificationPayloadPayee);

            if (!$responsePayer->successful() || $responsePayer->json("message") !== "Success") {
                 Log::warning("Falha ao enviar notificação para o pagador", ["transaction_id" => $transaction->id, "payload" => $notificationPayloadPayer, "response" => $responsePayer->body()]);
            }
             if (!$responsePayee->successful() || $responsePayee->json("message") !== "Success") {
                 Log::warning("Falha ao enviar notificação para o beneficiário", ["transaction_id" => $transaction->id, "payload" => $notificationPayloadPayee, "response" => $responsePayee->body()]);
            }

        } catch (\Exception $e) {
            Log::error("Falha ao enviar notificação pós-transferência", ["exception" => $e, "transaction_id" => $transaction->id]);
        }

        return redirect()->route("dashboard")->with("success", "Transferência realizada com sucesso!");
    }
}

