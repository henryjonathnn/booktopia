<?php

namespace Database\Seeders;

use App\Models\Dompet;
use App\Models\User;
use Illuminate\Database\Seeder;

class DompetSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user admin
        $admin = User::where('role', 'admin')->first();
        
        // Buat atau update dompet admin dengan saldo testing
        Dompet::updateOrCreate(
            ['id_user' => $admin->id],
            ['saldo' => 500000] // Contoh saldo awal 500rb untuk testing
        );
    }
} 