<?php

namespace App\Services;

class AuthorizationService
{
    public function authorize(float $amount, int $payerId, int $payeeId): array
    {
        $shouldAuthorize = (rand(1, 100) <= 95);
        
        if ($shouldAuthorize) {
            return [
                'authorized' => true,
                'message' => 'Autorizado',
                'authorization_code' => $this->generateAuthorizationCode(),
            ];
        } else {
            return [
                'authorized' => false,
                'message' => 'Não autorizado pelo serviço externo',
            ];
        }
    }
    
    private function generateAuthorizationCode(): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        
        for ($i = 0; $i < 10; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $code;
    }
}
