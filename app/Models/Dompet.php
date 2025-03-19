<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dompet extends Model
{
    use HasFactory;

    protected $fillable = [
        'saldo'
    ];

    protected $casts = [
        'saldo' => 'decimal:2'
    ];

    // Method untuk menambah saldo
    public function tambahSaldo($jumlah)
    {
        $this->increment('saldo', $jumlah);
    }

    // Method untuk mengurangi saldo
    public function kurangiSaldo($jumlah)
    {
        if ($this->saldo >= $jumlah) {
            $this->decrement('saldo', $jumlah);
            return true;
        }
        return false;
    }
}
