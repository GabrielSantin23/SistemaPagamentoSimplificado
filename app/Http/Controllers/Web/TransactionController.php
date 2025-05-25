<?php

namespace App\Http\Controllers\Web; // Assuming web context for now

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // For external service calls
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; // Added for logging notification errors

class TransactionController extends Controller
{
    // Mock URLs provided by the user
    private const AUTHORIZATION_MOCK_URL = "https://util.devi.tools/api/v2/authorize";
    private const NOTIFICATION_MOCK_URL = "https://util.devi.tools/api/v1/notify";

    /**
     * Show the form for creating a new transaction (transfer).
     */
    public function create()
    {
        // Get potential payees (all users except the logged-in user)
        // Exclude ADMIN users from being payees in the standard transfer form if needed
        $users = User::where("id", "!=", Auth::id())
                     // ->where("user_type", "!=", "ADMIN") // Optional: Uncomment to hide admins
                     ->get();
        // You need to create this view: resources/views/transactions/create.blade.php
        return view("transactions.create", compact("users"));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
    {
        $payer = Auth::user();

        // 1. Validate Request Data
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

        // 2. Validate Payer Type (Only COMUM users can send)
        if (!$payer->isComum()) {
            throw ValidationException::withMessages([
                "payer_id" => "Lojistas não podem realizar transferências, apenas receber."
            ]);
        }

        // 3. Validate Payer Balance
        if ($payer->balance < $value) {
            throw ValidationException::withMessages([
                "value" => "Saldo insuficiente para realizar a transferência."
            ]);
        }

        // 4. Start Database Transaction and Create Record
        $transaction = null;
        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                "payer_id" => $payer->id,
                "payee_id" => $payee->id,
                "value" => $value,
                "status" => "PENDING", // Start as PENDING
            ]);

            // 5. Consult External Authorizer Mock
            $authResponse = Http::get(self::AUTHORIZATION_MOCK_URL);

            if (!$authResponse->successful() || $authResponse->json("message") !== "Autorizado") {
                $transaction->update(["status" => "FAILED"]);
                DB::rollBack();
                Log::warning("Transferência não autorizada", ["transaction_id" => $transaction->id, "payer_id" => $payer->id, "payee_id" => $payee->id, "value" => $value]);
                throw ValidationException::withMessages([
                    "authorization" => "Transferência não autorizada pelo serviço externo."
                ]);
            }

            // Update transaction status to AUTHORIZED
            $transaction->update([
                "status" => "AUTHORIZED",
                "authorized_at" => now(),
            ]);

            // 6. Perform the Transfer (Update Balances)
            // Lock rows for update to prevent race conditions
            $payerLocked = User::where("id", $payer->id)->lockForUpdate()->first();
            $payeeLocked = User::where("id", $payee->id)->lockForUpdate()->first();

            // Re-check balance after locking
            if ($payerLocked->balance < $value) {
                 DB::rollBack();
                 $transaction->update(["status" => "FAILED"]);
                 throw ValidationException::withMessages([
                     "value" => "Saldo insuficiente após bloqueio para atualização."
                 ]);
            }

            $payerLocked->balance -= $value;
            $payeeLocked->balance += $value;
            $payerLocked->save();
            $payeeLocked->save();

            // 7. Update Transaction Status to COMPLETED
            $transaction->update([
                "status" => "COMPLETED",
                "completed_at" => now(),
            ]);

            DB::commit();

        } catch (\Exception $e) {
            // Rollback if transaction is still active
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            // Mark transaction as FAILED if it wasn't completed
            if ($transaction && $transaction->status !== "COMPLETED") {
                $transaction->status = "FAILED";
                $transaction->saveQuietly(); // Save without triggering events
            }
            Log::error("Erro durante a transferência", ["exception" => $e, "transaction_id" => $transaction->id ?? null]);
            // Re-throw a generic error message
            throw ValidationException::withMessages([
                "transfer" => "Ocorreu um erro inesperado durante a transferência. Tente novamente mais tarde."
                // "transfer" => "Ocorreu um erro durante a transferência: " . $e->getMessage() // More detailed error for debugging
            ]);
        }

        // 8. Send Notifications (using the provided mock) - Outside DB transaction
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

            // Optional: Check notification responses
            if (!$responsePayer->successful() || $responsePayer->json("message") !== "Success") {
                 Log::warning("Falha ao enviar notificação para o pagador", ["transaction_id" => $transaction->id, "payload" => $notificationPayloadPayer, "response" => $responsePayer->body()]);
            }
             if (!$responsePayee->successful() || $responsePayee->json("message") !== "Success") {
                 Log::warning("Falha ao enviar notificação para o beneficiário", ["transaction_id" => $transaction->id, "payload" => $notificationPayloadPayee, "response" => $responsePayee->body()]);
            }

        } catch (\Exception $e) {
            // Log notification error but don't fail the overall process
            Log::error("Falha ao enviar notificação pós-transferência", ["exception" => $e, "transaction_id" => $transaction->id]);
        }

        // Redirect to a success page or dashboard
        // TODO: Define appropriate success route (e.g., transaction history or dashboard)
        return redirect()->route("dashboard")->with("success", "Transferência realizada com sucesso!");
    }

    // TODO: Add index method to show transaction history for the logged-in user
    // public function index() { ... }
}

