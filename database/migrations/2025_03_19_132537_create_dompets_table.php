<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dompets', function (Blueprint $table) {
            $table->id();
            $table->decimal('saldo', 10, 2)->default(0);
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Insert saldo awal untuk admin
        $admin = DB::table('users')->where('role', 'ADMIN')->first();
        if ($admin) {
            DB::table('dompets')->insert([
                'saldo' => 0,
                'id_user' => $admin->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dompets');
    }
};
