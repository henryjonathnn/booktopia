<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle role-based access control
     * Mendukung pengecekan single role atau multiple roles
     * Format: 'role:ADMIN' atau 'role:ADMIN,STAFF'
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $userRole = auth()->user()->role;
        
        // Cek apakah role user ada dalam daftar roles yang diizinkan
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Redirect jika tidak punya akses
        return redirect()->route('home')->with('error', 'Unauthorized access');
    }
}