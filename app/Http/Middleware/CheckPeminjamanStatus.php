<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPeminjamanStatus
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Cek apakah ada peminjaman terlambat
        $hasOverdue = $user->peminjamans()
            ->where('status', 'TERLAMBAT')
            ->where('is_denda_paid', false)
            ->exists();

        if ($hasOverdue) {
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Anda memiliki peminjaman yang terlambat. Harap selesaikan denda terlebih dahulu.'
            ]);
            return redirect()->route('peminjaman');
        }

        return $next($request);
    }
} 