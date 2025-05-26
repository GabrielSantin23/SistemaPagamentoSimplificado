<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

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
            "name" => fake()->name(),
            "cpf_cnpj" => $cpfCnpj,
            "email" => fake()->unique()->safeEmail(),
            "password" => static::$password ??= Hash::make("password"),
            "user_type" => fake()->randomElement(["COMUM", "LOJISTA"]),
            "wallet" => fake()->randomFloat(2, 50, 1000),
            "remember_token" => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            "user_type" => "ADMIN",
            "wallet" => 10000.00,
        ]);
    }

    public function comum(): static
    {
        return $this->state(fn (array $attributes) => [
            "user_type" => "COMUM",
        ]);
    }

    public function lojista(): static
    {
        return $this->state(fn (array $attributes) => [
            "user_type" => "LOJISTA",
        ]);
    }


    private function generateCpf(): string
    {
        return sprintf("%03d%03d%03d%02d", rand(0, 999), rand(0, 999), rand(0, 999), rand(0, 99));
    }

    private function generateCnpj(): string
    {
        return sprintf("%02d%03d%03d%04d%02d", rand(0, 99), rand(0, 999), rand(0, 999), rand(0, 9999), rand(0, 99));
    }
}

