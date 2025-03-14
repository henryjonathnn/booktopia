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
    public $isRejectModalOpen = false;
    public $selectedPeminjamanId;
    public $alasanPenolakan;
    public $buktiPengiriman;
    public $statuses;
    public $metodes;
    public $isDetailModalOpen = false;
    public $selectedPeminjaman = null;

    protected $rules = [
        'alasanPenolakan' => 'required|min:10',
        'buktiPengiriman' => 'required|image|max:10240', // max 10MB
    ];

    protected $messages = [
        'alasanPenolakan.required' => 'Alasan penolakan wajib diisi',
        'alasanPenolakan.min' => 'Alasan penolakan minimal 10 karakter',
        'buktiPengiriman.required' => 'Bukti pengiriman wajib diupload',
        'buktiPengiriman.image' => 'File harus berupa gambar',
        'buktiPengiriman.max' => 'Ukuran file maksimal 10MB',
    ];

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

    public function openRejectModal($peminjamanId)
    {
        $this->selectedPeminjamanId = $peminjamanId;
        $this->isRejectModalOpen = true;
    }

    public function closeRejectModal()
    {
        $this->isRejectModalOpen = false;
        $this->selectedPeminjamanId = null;
        $this->alasanPenolakan = '';
        $this->resetValidation('alasanPenolakan');
    }

    public function approvePeminjaman($peminjamanId)
    {
        $peminjaman = Peminjaman::findOrFail($peminjamanId);
        
        if ($peminjaman->status !== 'PENDING') {
            session()->flash('error', 'Status peminjaman tidak valid untuk disetujui');
            return;
        }

        $peminjaman->status = 'DIPROSES';
        $peminjaman->id_staff = auth()->id();
        $peminjaman->save();

        // Kirim notifikasi ke user
        Notifikasi::create([
            'id_user' => $peminjaman->id_user,
            'id_peminjaman' => $peminjaman->id,
            'message' => "Peminjaman buku {$peminjaman->buku->judul} telah disetujui dan sedang diproses",
            'tipe' => 'PEMINJAMAN_DIPROSES'
        ]);

        session()->flash('success', 'Peminjaman berhasil disetujui');
    }

    public function rejectPeminjaman()
    {
        $this->validate([
            'alasanPenolakan' => 'required|min:10'
        ]);

        $peminjaman = Peminjaman::findOrFail($this->selectedPeminjamanId);
        
        if ($peminjaman->status !== 'PENDING') {
            session()->flash('error', 'Status peminjaman tidak valid untuk ditolak');
            return;
        }

        // Kembalikan stok buku
        $peminjaman->buku->increment('stock');
        
        $peminjaman->status = 'DITOLAK';
        $peminjaman->alasan_penolakan = $this->alasanPenolakan;
        $peminjaman->id_staff = auth()->id();
        $peminjaman->save();

        // Kirim notifikasi ke user
        Notifikasi::create([
            'id_user' => $peminjaman->id_user,
            'id_peminjaman' => $peminjaman->id,
            'message' => "Peminjaman buku {$peminjaman->buku->judul} ditolak dengan alasan: {$this->alasanPenolakan}",
            'tipe' => 'PEMINJAMAN_DITOLAK'
        ]);

        $this->closeRejectModal();
        session()->flash('success', 'Peminjaman berhasil ditolak');
    }

    public function uploadBuktiPengiriman($peminjamanId)
    {
        $this->validate([
            'buktiPengiriman' => 'required|image|max:10240'
        ]);

        try {
            $peminjaman = Peminjaman::findOrFail($peminjamanId);
            
            if ($peminjaman->status !== 'DIPROSES') {
                session()->flash('error', 'Status peminjaman tidak valid untuk dikirim');
                return;
            }

            // Upload bukti pengiriman
            $path = $this->buktiPengiriman->store('bukti-pengiriman', 'public');
            
            // Update status peminjaman
            $peminjaman->bukti_pengiriman = $path;
            $peminjaman->status = 'DIKIRIM';
            $peminjaman->tgl_dikirim = now();
            $peminjaman->save();

            // Kirim notifikasi ke user
            Notifikasi::create([
                'id_user' => $peminjaman->id_user,
                'id_peminjaman' => $peminjaman->id,
                'message' => "Peminjaman buku {$peminjaman->buku->judul} telah dikirim. Silakan cek bukti pengiriman",
                'tipe' => 'PEMINJAMAN_DIKIRIM'
            ]);

            $this->buktiPengiriman = null;
            session()->flash('success', 'Bukti pengiriman berhasil diupload');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mengupload bukti pengiriman');
        }
    }

    public function openDetailModal($peminjamanId)
    {
        $this->selectedPeminjaman = Peminjaman::with(['user', 'buku', 'staff'])->find($peminjamanId);
        $this->isDetailModalOpen = true;
    }

    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->selectedPeminjaman = null;
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

        $peminjamans = $query->paginate($this->perPage);

        return view('livewire.admin.data-peminjaman', [
            'peminjamans' => $peminjamans
        ]);
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