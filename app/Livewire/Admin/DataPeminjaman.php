<?php

namespace App\Livewire\Admin;

use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;
use App\Models\Notifikasi;
use App\Events\PeminjamanStatusChanged;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DataPeminjaman extends Component
{
    use WithPagination, WithFileUploads;

    // For pagination
    protected $paginationTheme = 'tailwind';
    
    // Search & Filter Properties
    public $search = '';
    public $status = '';
    public $metode = '';
    public $perPage = 10;
    public $dateRange = '';
    
    // Modal control properties
    public $activeModal = null; // 'detail', 'reject', atau null
    public $selectedPeminjaman = null;
    public $alasanPenolakan = '';
    public $buktiPengiriman;
    public $statuses;
    public $metodes;
    public $uploadingPeminjamanId;
    public $showDetailModal = false;
    public $showExportModal = false;
    public $exportStatus = '';
    public $exportDateStart = '';
    public $exportDateEnd = '';

    public $statusColors = [
        'PENDING' => 'yellow',
        'DIPROSES' => 'blue',
        'DIKIRIM' => 'green',
        'DIPINJAM' => 'purple',
        'TERLAMBAT' => 'red',
        'DIKEMBALIKAN' => 'gray',
        'DITOLAK' => 'red'
    ];

    protected $listeners = [
        'refresh' => '$refresh'
    ];

    protected function rules()
    {
        return [
            'alasanPenolakan' => 'required|min:10',
            'buktiPengiriman' => 'nullable|image|max:10240'
        ];
    }

    public function mount()
    {
        $this->statuses = [
            'PENDING',
            'DIPROSES',
            'DIKIRIM',
            'DIPINJAM',
            'TERLAMBAT',
            'DIKEMBALIKAN',
            'DITOLAK'
        ];

        $this->metodes = [
            'KURIR',
            'AMBIL_DI_TEMPAT'
        ];
    }

    // Reset semua state modal
    private function resetModalStates()
    {
        $this->activeModal = null;
        $this->selectedPeminjaman = null;
        $this->alasanPenolakan = '';
        $this->buktiPengiriman = null;
        $this->resetValidation();
    }

    // Handler untuk modal detail
    public function showDetail($peminjamanId)
    {
        $this->selectedPeminjaman = Peminjaman::with(['user', 'buku'])->find($peminjamanId);
        if ($this->selectedPeminjaman) {
            $this->showDetailModal = true;
        }
    }

    // Handler untuk modal reject
    public function showReject($peminjamanId)
    {
        $this->resetModalStates();
        $this->selectedPeminjaman = Peminjaman::find($peminjamanId);
        $this->activeModal = 'reject';
    }

    // Handler untuk menutup modal
    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedPeminjaman = null;
    }

    // Handler untuk approve peminjaman
    public function approvePeminjaman($peminjamanId)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($peminjamanId);
            
            if ($peminjaman->status !== 'PENDING') {
                session()->flash('error', 'Status peminjaman tidak valid untuk disetujui');
                return;
            }

            $peminjaman->status = 'DIPROSES';
            $peminjaman->id_staff = auth()->id();
            $peminjaman->save();

            Notifikasi::create([
                'id_user' => $peminjaman->id_user,
                'id_peminjaman' => $peminjaman->id,
                'message' => "Peminjaman buku {$peminjaman->buku->judul} telah disetujui dan sedang diproses",
                'tipe' => 'PEMINJAMAN_DIPROSES'
            ]);

            session()->flash('success', 'Peminjaman berhasil disetujui');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menyetujui peminjaman');
        }
    }

    // Handler untuk reject peminjaman
    public function rejectPeminjaman()
    {
        $this->validate();

        try {
            $peminjaman = $this->selectedPeminjaman;
            
            if ($peminjaman->status !== 'PENDING') {
                session()->flash('error', 'Status peminjaman tidak valid untuk ditolak');
                return;
            }

            // $peminjaman->buku->increment('stock');
            $peminjaman->status = 'DITOLAK';
            $peminjaman->alasan_penolakan = $this->alasanPenolakan;
            $peminjaman->id_staff = auth()->id();
            $peminjaman->save();

            Notifikasi::create([
                'id_user' => $peminjaman->id_user,
                'id_peminjaman' => $peminjaman->id,
                'message' => "Peminjaman buku {$peminjaman->buku->judul} ditolak dengan alasan: {$this->alasanPenolakan}",
                'tipe' => 'PEMINJAMAN_DITOLAK'
            ]);

            $this->resetModalStates();
            session()->flash('success', 'Peminjaman berhasil ditolak');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menolak peminjaman');
        }
    }

    // Handler untuk modal upload bukti pengiriman
    public function showUpload($peminjamanId)
    {
        $this->resetModalStates();
        $this->selectedPeminjaman = Peminjaman::find($peminjamanId);
        $this->uploadingPeminjamanId = $peminjamanId;
        $this->activeModal = 'upload';
    }

    // Handler untuk upload bukti pengiriman
    public function uploadBuktiPengiriman()
    {
        $this->validate([
            'buktiPengiriman' => 'required|image|max:10240'
        ]);

        try {
            $peminjaman = Peminjaman::findOrFail($this->uploadingPeminjamanId);
            
            if ($peminjaman->status !== 'DIPROSES') {
                session()->flash('error', 'Status peminjaman tidak valid untuk dikirim');
                return;
            }

            $path = $this->buktiPengiriman->store('bukti-pengiriman', 'public');
            
            $peminjaman->bukti_pengiriman = $path;
            $peminjaman->status = 'DIKIRIM';
            $peminjaman->tgl_dikirim = now();
            $peminjaman->save();

            Notifikasi::create([
                'id_user' => $peminjaman->id_user,
                'id_peminjaman' => $peminjaman->id,
                'message' => "Peminjaman buku {$peminjaman->buku->judul} telah dikirim. Silakan cek bukti pengiriman",
                'tipe' => 'PEMINJAMAN_DIKIRIM'
            ]);

            $this->resetModalStates();
            session()->flash('success', 'Bukti pengiriman berhasil diupload');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mengupload bukti pengiriman');
        }
    }

    public function confirmMarkAsDipinjam($peminjamanId)
    {
        $this->dispatch('showConfirmation', ['peminjamanId' => $peminjamanId]);
    }

    public function markAsDipinjam($peminjamanId)
    {
        try {
            DB::beginTransaction();
            
            $peminjaman = Peminjaman::findOrFail($peminjamanId);
            
            if ($peminjaman->status !== 'DIKIRIM') {
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Status peminjaman harus DIKIRIM untuk bisa diubah menjadi DIPINJAM'
                ]);
                return;
            }

            // Update status dan tanggal
            $peminjaman->update([
                'status' => 'DIPINJAM',
                'tgl_peminjaman_aktual' => now()
            ]);

            // Buat notifikasi untuk user
            Notifikasi::create([
                'id_user' => $peminjaman->id_user,
                'id_peminjaman' => $peminjaman->id,
                'message' => "Buku {$peminjaman->buku->judul} telah dipinjam pada tanggal " . 
                            now()->format('d M Y') . ". Batas pengembalian: " . 
                            Carbon::parse($peminjaman->tgl_kembali_rencana)->format('d M Y'),
                'tipe' => 'PEMINJAMAN_DITERIMA'
            ]);

            // Trigger event untuk notifikasi
            event(new PeminjamanStatusChanged($peminjaman));

            DB::commit();

            // Refresh data
            $this->dispatch('refresh');
            
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error in markAsDipinjam: ' . $e->getMessage());
        }
    }

    public function processPeminjaman($peminjamanId)
    {
        try {
            DB::beginTransaction();
            
            $peminjaman = Peminjaman::findOrFail($peminjamanId);
            
            // Pastikan status saat ini adalah PENDING
            if ($peminjaman->status !== 'PENDING') {
                session()->flash('alert', [
                    'type' => 'error',
                    'message' => 'Status peminjaman tidak valid untuk diproses!'
                ]);
                return;
            }

            // Cek stok buku
            if ($peminjaman->buku->stock <= 0) {
                session()->flash('alert', [
                    'type' => 'error',
                    'message' => 'Stok buku tidak mencukupi!'
                ]);
                return;
            }

            // Kurangi stok buku
            $peminjaman->buku->decrement('stock');

            // Update status menjadi DIPROSES
            $peminjaman->update([
                'status' => 'DIPROSES',
                'id_staff' => auth()->id()
            ]);

            DB::commit();
            
            session()->flash('alert', [
                'type' => 'success',
                'message' => 'Peminjaman berhasil diproses!'
            ]);

            $this->dispatch('refresh');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Gagal memproses peminjaman: ' . $e->getMessage()
            ]);
        }
    }

    public function sendPeminjaman($peminjamanId)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($peminjamanId);
            
            if ($peminjaman->status !== 'DIPROSES') {
                session()->flash('alert', [
                    'type' => 'error',
                    'message' => 'Status peminjaman tidak valid untuk dikirim!'
                ]);
                return;
            }

            $peminjaman->update([
                'status' => 'DIKIRIM',
                'tanggal_pengiriman' => now()
            ]);

            session()->flash('alert', [
                'type' => 'success',
                'message' => 'Status peminjaman berhasil diupdate ke pengiriman!'
            ]);

            $this->dispatch('refresh');

        } catch (\Exception $e) {
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ]);
        }
    }

    public function returnPeminjaman($peminjamanId)
    {
        try {
            DB::beginTransaction();
            
            $peminjaman = Peminjaman::findOrFail($peminjamanId);
            
            if ($peminjaman->status !== 'DIKIRIM') {
                session()->flash('alert', [
                    'type' => 'error',
                    'message' => 'Status peminjaman tidak valid untuk dikembalikan!'
                ]);
                return;
            }

            // Tambah stok buku
            $peminjaman->buku->increment('stock');

            $peminjaman->update([
                'status' => 'DIKEMBALIKAN',
                'tanggal_pengembalian' => now()
            ]);

            DB::commit();

            session()->flash('alert', [
                'type' => 'success',
                'message' => 'Buku berhasil dikembalikan!'
            ]);

            $this->dispatch('refresh');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Gagal mengembalikan buku: ' . $e->getMessage()
            ]);
        }
    }

    public function showExportOptions()
    {
        $this->showExportModal = true;
    }

    public function closeExportModal()
    {
        $this->showExportModal = false;
        $this->reset(['exportStatus', 'exportDateStart', 'exportDateEnd']);
    }

    public function getExportData()
    {
        $this->validate([
            'exportDateStart' => 'required_with:exportDateEnd|date',
            'exportDateEnd' => 'required_with:exportDateStart|date|after_or_equal:exportDateStart',
        ]);

        $query = Peminjaman::with(['user', 'buku', 'staff'])
            ->when($this->exportStatus, function ($query) {
                $query->where('status', $this->exportStatus);
            })
            ->when($this->exportDateStart && $this->exportDateEnd, function ($query) {
                $query->whereBetween('created_at', [
                    Carbon::parse($this->exportDateStart)->startOfDay(),
                    Carbon::parse($this->exportDateEnd)->endOfDay()
                ]);
            })
            ->latest();

        $peminjamans = $query->get();

        return [
            'peminjamans' => $peminjamans->map(function($peminjaman) {
                return [
                    'id' => $peminjaman->id,
                    'created_at' => $peminjaman->created_at,
                    'tanggal_pengiriman' => $peminjaman->tanggal_pengiriman,
                    'tanggal_pengembalian' => $peminjaman->tanggal_pengembalian,
                    'metode_pengiriman' => $peminjaman->metode_pengiriman,
                    'status' => $peminjaman->status,
                    'buku' => [
                        'judul' => $peminjaman->buku->judul,
                        'penulis' => $peminjaman->buku->penulis,
                        'kategori' => $peminjaman->buku->kategori,
                        'isbn' => $peminjaman->buku->isbn,
                    ],
                    'user' => [
                        'name' => $peminjaman->user->name,
                        'email' => $peminjaman->user->email,
                        'phone' => $peminjaman->user->phone,
                        'alamat' => $peminjaman->user->alamat,
                    ],
                ];
            })->toArray(),
            'status' => $this->exportStatus ?: 'Semua Status',
            'dateStart' => $this->exportDateStart ? Carbon::parse($this->exportDateStart)->format('d M Y') : 'All',
            'dateEnd' => $this->exportDateEnd ? Carbon::parse($this->exportDateEnd)->format('d M Y') : 'All',
            'timestamp' => now()->format('d M Y H:i:s'),
        ];
    }

    public function render()
    {
        $query = Peminjaman::with(['user', 'buku', 'staff'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('buku', function ($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->metode, function ($query) {
                $query->where('metode_pengiriman', $this->metode);
            })
            ->latest();

        return view('livewire.admin.data-peminjaman', [
            'peminjamans' => $query->paginate($this->perPage),
            'statuses' => ['PENDING', 'DIPROSES', 'DIKIRIM', 'DIPINJAM', 'TERLAMBAT', 'DIKEMBALIKAN', 'DITOLAK'],
            'metodes' => ['AMBIL_DITEMPAT', 'DIANTAR']
        ])->layout('layouts.admin', ['title' => 'Data Peminjaman']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingMetode()
    {
        $this->resetPage();
    }
} 