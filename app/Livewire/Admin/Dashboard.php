<?php

namespace App\Livewire\Admin;

use App\Models\Buku;
use Livewire\Component;
use App\Models\User;
use App\Models\Peminjaman;

class Dashboard extends Component
{
    public $totalUsers = 0;
    public $totalBooks = 0;
    public $totalLoans = 0;
    public $activeLoans = 0;
    public $monthlyStats = [];

    public function mount()
    {
        // Count totals (using basic counts for testing)
        $this->totalUsers = User::count();
        $this->totalBooks = Buku::count();
        $this->totalLoans = Peminjaman::count();
        $this->activeLoans = Peminjaman::where('status', 'active')->count();

        // Generate some sample monthly stats for chart
        $this->monthlyStats = [
            ['month' => 'Jan', 'loans' => 45, 'returns' => 38],
            ['month' => 'Feb', 'loans' => 52, 'returns' => 43],
            ['month' => 'Mar', 'loans' => 48, 'returns' => 41],
            ['month' => 'Apr', 'loans' => 61, 'returns' => 52],
            ['month' => 'May', 'loans' => 55, 'returns' => 49],
            ['month' => 'Jun', 'loans' => 67, 'returns' => 59],
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}