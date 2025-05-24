<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        $isCnpj = fake()->boolean();
        $cpfCnpj = $isCnpj
            ? $this->generateCnpj()
            : $this->generateCpf();

        return [
            'name' => fake()->name(),
            'cpf_cnpj' => $cpfCnpj,
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'user_type' => fake()->randomElement(['COMUM', 'LOJISTA']),
        ];
    }

    private function generateCpf(): string
    {
        return str_pad(rand(0, 99999999999), 11, '0', STR_PAD_LEFT);
    }

    private function generateCnpj(): string
    {
        return str_pad(rand(0, 99999999999999), 14, '0', STR_PAD_LEFT);
    }
}
