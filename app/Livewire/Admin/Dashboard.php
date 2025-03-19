<?php

namespace App\Livewire\Admin;

use App\Models\Buku;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $totalUsers = 0;
    public $totalBooks = 0;
    public $totalLoans = 0;
    public $activeLoans = 0;
    public $monthlyStats = [];
    
    public function mount()
    {
        // Hitung total users (non-admin)
        $this->totalUsers = User::where('role', 'USER')->count();
        
        // Hitung total buku (jumlah judul buku, bukan stok)
        $this->totalBooks = Buku::count();
        
        // Hitung total peminjaman
        $this->totalLoans = Peminjaman::count();
        
        // Hitung peminjaman aktif (status DIPINJAM)
        $this->activeLoans = Peminjaman::whereIn('status', ['DIPINJAM', 'TERLAMBAT'])->count();

        // Generate statistik bulanan untuk 6 bulan terakhir
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        
        // Ambil data peminjaman per bulan
        $monthlyLoans = Peminjaman::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as total_loans')
        )
        ->where('created_at', '>=', $sixMonthsAgo)
        ->groupBy('year', 'month')
        ->get()
        ->keyBy(function ($item) {
            return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
        });

        // Ambil data pengembalian per bulan
        $monthlyReturns = Peminjaman::select(
            DB::raw('MONTH(updated_at) as month'),
            DB::raw('YEAR(updated_at) as year'),
            DB::raw('COUNT(*) as total_returns')
        )
        ->where('status', 'DIKEMBALIKAN')
        ->where('updated_at', '>=', $sixMonthsAgo)
        ->groupBy('year', 'month')
        ->get()
        ->keyBy(function ($item) {
            return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
        });

        // Buat array untuk 6 bulan terakhir
        $monthlyStats = [];
        for ($i = 0; $i < 6; $i++) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            
            $monthlyStats[] = [
                'month' => $date->format('M'),
                'year' => $date->year,
                'month_num' => $date->month,
                'loans' => $monthlyLoans->get($key)?->total_loans ?? 0,
                'returns' => $monthlyReturns->get($key)?->total_returns ?? 0
            ];
        }

        // Balik urutan array agar dimulai dari bulan terlama
        $this->monthlyStats = array_reverse($monthlyStats);
    }

    public function getRecentActivities()
    {
        return Peminjaman::with(['user', 'buku'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($peminjaman) {
                return [
                    'user' => [
                        'name' => $peminjaman->user->name,
                        'email' => $peminjaman->user->email,
                        'initials' => strtoupper(substr($peminjaman->user->name, 0, 2))
                    ],
                    'book' => [
                        'title' => $peminjaman->buku->judul,
                        'author' => $peminjaman->buku->penulis
                    ],
                    'action' => $this->getActionText($peminjaman->status),
                    'date' => $peminjaman->created_at->format('M d, Y'),
                    'status' => $peminjaman->status,
                    'status_color' => $this->getStatusColor($peminjaman->status)
                ];
            });
    }

    private function getActionText($status)
    {
        return match($status) {
            'PENDING' => 'Mengajukan Peminjaman',
            'DIPROSES' => 'Sedang Diproses',
            'DIKIRIM' => 'Dalam Pengiriman',
            'DIPINJAM' => 'Meminjam',
            'TERLAMBAT' => 'Terlambat',
            'DIKEMBALIKAN' => 'Mengembalikan',
            'DITOLAK' => 'Ditolak',
            default => 'Unknown'
        };
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'PENDING' => 'yellow',
            'DIPROSES' => 'blue',
            'DIKIRIM' => 'indigo',
            'DIPINJAM' => 'green',
            'TERLAMBAT' => 'red',
            'DIKEMBALIKAN' => 'gray',
            'DITOLAK' => 'red',
            default => 'gray'
        };
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'recentActivities' => $this->getRecentActivities()
        ])->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}