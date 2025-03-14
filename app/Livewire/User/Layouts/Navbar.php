<?php

namespace App\Livewire\User\Layouts;

use App\Models\Notifikasi;
use Livewire\Component;

class Navbar extends Component
{
    public $showNotifikasi = false;
    public $showDetailModal = false;
    public $selectedNotifikasi = null;
    public $unreadCount;
    public $notifikasi;
    public $search = '';

    protected $listeners = [
        'refreshNotifikasi' => 'refreshNotifikasi',
        'searchUpdated' => 'handleSearch'
    ];

    public function mount()
    {
        $this->refreshNotifikasi();
    }

    public function updatedSearch()
    {
        $this->dispatch('searchUpdated', $this->search);
    }

    public function handleSearch($search)
    {
        $this->search = $search;
    }

    public function toggleNotifikasi()
    {
        $this->showNotifikasi = !$this->showNotifikasi;
        if ($this->showNotifikasi) {
            $this->showDetailModal = false;
            $this->selectedNotifikasi = null;
        }
    }

    public function refreshNotifikasi()
    {
        $this->notifikasi = auth()->user()->notifikasi()->latest()->take(5)->get();
        $this->unreadCount = auth()->user()->notifikasi()->where('is_read', false)->count();
    }

    public function showDetail($notifId)
    {
        $this->selectedNotifikasi = Notifikasi::with('peminjaman.buku')->find($notifId);
        if (!$this->selectedNotifikasi->is_read) {
            $this->selectedNotifikasi->markAsRead();
            $this->refreshNotifikasi();
        }
        $this->showDetailModal = true;
        $this->showNotifikasi = false;
    }

    public function closeDetail()
    {
        $this->selectedNotifikasi = null;
        $this->showDetailModal = false;
    }

    public function markAllAsRead()
    {
        auth()->user()->notifikasi()->where('is_read', false)->update(['is_read' => true]);
        $this->refreshNotifikasi();
    }

    public function render()
    {
        return view('livewire.user.layouts.navbar');
    }
}