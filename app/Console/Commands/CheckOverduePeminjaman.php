<?php

namespace App\Console\Commands;

use App\Jobs\ProcessOverduePeminjaman;
use Illuminate\Console\Command;

class CheckOverduePeminjaman extends Command
{
    protected $signature = 'peminjaman:check-overdue';
    protected $description = 'Check for overdue peminjaman and calculate denda';

    public function handle()
    {
        ProcessOverduePeminjaman::dispatch();
        $this->info('Overdue check job dispatched successfully');
    }
} 