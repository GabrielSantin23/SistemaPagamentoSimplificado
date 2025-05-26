<?php

namespace App\Services;

class NotificationService
{
    public function notify(int $userId, int $transactionId, string $type, float $amount): array
    {
        $isSuccessful = (rand(1, 100) <= 98);
        
        if ($isSuccessful) {
            return [
                'success' => true,
                'message' => 'Success',
                'notification_id' => $this->generateNotificationId(),
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Falha ao enviar notificação',
            ];
        }
    }
    
    private function generateNotificationId(): string
    {
        return uniqid('notif_', true);
    }
}
