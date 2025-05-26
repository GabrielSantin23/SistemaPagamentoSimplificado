<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        User::factory()->admin()->create([
            "name" => "Admin User",
            "email" => "admin@example.com",
            "password" => Hash::make("password"),
            "cpf_cnpj" => "00000000000",
        ]);

        User::factory(5)->comum()->create();

        User::factory(3)->lojista()->create();
    }
}

