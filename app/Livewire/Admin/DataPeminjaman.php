<?php

namespace App\Livewire\Admin;

use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;
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
    public $isModalOpen = false;
    public $isDetailModalOpen = false;
    public $confirmingPeminjamanDeletion = false;
    public $peminjamanIdToDelete = null;
    
    // Form properties
    public $peminjamanId = null;
    public $idUser = '';
    public $idBuku = '';
    public $idStaff = '';
    public $alamatPengiriman = '';
    public $catatanPengiriman = '';
    public $tglPeminjamanDiinginkan = '';
    public $buktiPengiriman = null;
    public $existingBuktiPengiriman = null;
    public $tglDikirim = null;
    public $tglKembaliRencana = null;
    public $tglKembaliAktual = null;
    public $peminjamanStatus = 'PENDING';
    public $metodePengiriman = 'KURIR';
    public $totalDenda = 0;
    public $buktiPembayaranDenda = null;
    public $existingBuktiPembayaranDenda = null;
    public $alasanPenolakan = '';
    public $nomorResi = '';
    
    // Selected peminjaman for batch operations
    public $selectedPeminjamans = [];
    public $selectAll = false;
    
    // Computed property for all peminjamans on current page
    public $peminjamansOnCurrentPage = [];
    
    // Cache selected peminjaman for detail view
    public $selectedPeminjaman = null;

    // Available statuses from database
    public $statuses = [
        'PENDING',
        'DIPROSES',
        'DIKIRIM',
        'DIPINJAM',
        'TERLAMBAT',
        'DIKEMBALIKAN',
        'DITOLAK'
    ];

    // Available metode pengiriman
    public $metodes = [
        'KURIR',
        'AMBIL_DI_TEMPAT'
    ];

    protected $listeners = ['refreshPeminjamans' => '$refresh'];

    public function mount()
    {
        $this->resetPage();
        $this->tglPeminjamanDiinginkan = now()->format('Y-m-d');
        $this->tglKembaliRencana = now()->addDays(7)->format('Y-m-d');
    }

    // Define validation rules
    protected function rules()
    {
        return [
            'idUser' => 'required|exists:users,id',
            'idBuku' => 'required|exists:bukus,id',
            'idStaff' => 'nullable|exists:users,id',
            'alamatPengiriman' => 'required|string|min:10',
            'catatanPengiriman' => 'nullable|string',
            'tglPeminjamanDiinginkan' => 'required|date',
            'buktiPengiriman' => 'nullable|image|max:2048',
            'tglDikirim' => 'nullable|date',
            'tglKembaliRencana' => 'required|date|after_or_equal:tglPeminjamanDiinginkan',
            'tglKembaliAktual' => 'nullable|date',
            'peminjamanStatus' => 'required|in:PENDING,DIPROSES,DIKIRIM,DIPINJAM,TERLAMBAT,DIKEMBALIKAN,DITOLAK',
            'metodePengiriman' => 'required|in:KURIR,AMBIL_DI_TEMPAT',
            'totalDenda' => 'nullable|integer|min:0',
            'buktiPembayaranDenda' => 'nullable|image|max:2048',
            'alasanPenolakan' => 'nullable|string|required_if:peminjamanStatus,DITOLAK',
            'nomorResi' => 'nullable|string|required_if:peminjamanStatus,DIKIRIM',
        ];
    }

    protected $validationAttributes = [
        'idUser' => 'user',
        'idBuku' => 'buku',
        'idStaff' => 'staff',
        'alamatPengiriman' => 'alamat pengiriman',
        'catatanPengiriman' => 'catatan pengiriman',
        'tglPeminjamanDiinginkan' => 'tanggal peminjaman diinginkan',
        'buktiPengiriman' => 'bukti pengiriman',
        'tglDikirim' => 'tanggal dikirim',
        'tglKembaliRencana' => 'tanggal kembali rencana',
        'tglKembaliAktual' => 'tanggal kembali aktual',
        'peminjamanStatus' => 'status',
        'metodePengiriman' => 'metode pengiriman',
        'totalDenda' => 'total denda',
        'buktiPembayaranDenda' => 'bukti pembayaran denda',
        'alasanPenolakan' => 'alasan penolakan',
        'nomorResi' => 'nomor resi',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedMetode()
    {
        $this->resetPage();
    }

    public function updatedDateRange()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedPeminjamans = $this->peminjamansOnCurrentPage;
        } else {
            $this->selectedPeminjamans = [];
        }
    }

    // Get form configuration for the peminjaman form
    public function getPeminjamanFormConfig()
    {
        return [
            [
                'id' => 'idUser',
                'label' => 'User',
                'type' => 'select',
                'required' => true,
                'options' => User::where('is_active', true)->where('role', 'USER')->pluck('name', 'id')->toArray()
            ],
            [
                'id' => 'idBuku',
                'label' => 'Buku',
                'type' => 'select',
                'required' => true,
                'options' => Buku::where('stock', '>', 0)->pluck('judul', 'id')->toArray()
            ],
            [
                'id' => 'alamatPengiriman',
                'label' => 'Alamat Pengiriman',
                'type' => 'textarea',
                'required' => true
            ],
            [
                'id' => 'catatanPengiriman',
                'label' => 'Catatan Pengiriman',
                'type' => 'textarea',
                'required' => false
            ],
            [
                'id' => 'tglPeminjamanDiinginkan',
                'label' => 'Tanggal Peminjaman',
                'type' => 'date',
                'required' => true
            ],
            [
                'id' => 'tglKembaliRencana',
                'label' => 'Tanggal Kembali Rencana',
                'type' => 'date',
                'required' => true
            ],
            [
                'id' => 'metodePengiriman',
                'label' => 'Metode Pengiriman',
                'type' => 'select',
                'required' => true,
                'options' => $this->metodes
            ],
            [
                'id' => 'peminjamanStatus',
                'label' => 'Status',
                'type' => 'select',
                'required' => true,
                'options' => $this->statuses
            ],
        ];
    }

    // Get additional form fields based on status
    public function getAdditionalFormFields()
    {
        $fields = [];
        
        if ($this->peminjamanStatus === 'DITOLAK') {
            $fields[] = [
                'id' => 'alasanPenolakan',
                'label' => 'Alasan Penolakan',
                'type' => 'textarea',
                'required' => true
            ];
        }
        
        if ($this->peminjamanStatus === 'DIKIRIM') {
            $fields[] = [
                'id' => 'tglDikirim',
                'label' => 'Tanggal Dikirim',
                'type' => 'date',
                'required' => true
            ];
            
            $fields[] = [
                'id' => 'nomorResi',
                'label' => 'Nomor Resi',
                'type' => 'text',
                'required' => true
            ];
        }
        
        if ($this->peminjamanStatus === 'TERLAMBAT' || $this->peminjamanStatus === 'DIKEMBALIKAN') {
            $fields[] = [
                'id' => 'totalDenda',
                'label' => 'Total Denda',
                'type' => 'number',
                'required' => true
            ];
        }
        
        if ($this->peminjamanStatus === 'DIKEMBALIKAN') {
            $fields[] = [
                'id' => 'tglKembaliAktual',
                'label' => 'Tanggal Kembali Aktual',
                'type' => 'date',
                'required' => true
            ];
        }
        
        return $fields;
    }

    public function render()
    {
        $query = Peminjaman::with(['user', 'buku', 'staff']);
        
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('user', function($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('buku', function($bukuQuery) {
                    $bukuQuery->where('judul', 'like', '%' . $this->search . '%');
                })
                ->orWhere('nomor_resi', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        if ($this->metode) {
            $query->where('metode_pengiriman', $this->metode);
        }
        
        if ($this->dateRange) {
            $dates = explode(' to ', $this->dateRange);
            if (count($dates) === 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate = Carbon::parse($dates[1])->endOfDay();
                $query->whereBetween('tgl_peminjaman_diinginkan', [$startDate, $endDate]);
            }
        }
        
        $peminjamans = $query->orderBy('created_at', 'desc')
                       ->paginate($this->perPage);
            
        // Save IDs of peminjamans on current page for "Select All" functionality
        $this->peminjamansOnCurrentPage = $peminjamans->pluck('id')->toArray();
            
        return view('livewire.admin.data-peminjaman', [
            'peminjamans' => $peminjamans,
            'formConfig' => $this->getPeminjamanFormConfig(),
            'additionalFormFields' => $this->getAdditionalFormFields(),
            'currentPeminjaman' => $this->peminjamanId ? Peminjaman::find($this->peminjamanId) : null
        ])->layout('layouts.admin', ['title' => 'Data Peminjaman']);
    }

    // Open modal to create a new peminjaman
    public function createPeminjaman()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isModalOpen = true;
    }

    // Open modal to edit a peminjaman
    public function editPeminjaman($peminjamanId)
    {
        $this->resetValidation();
        $this->resetForm();
        
        $this->peminjamanId = $peminjamanId;
        $peminjaman = Peminjaman::findOrFail($peminjamanId);
        
        $this->idUser = $peminjaman->id_user;
        $this->idBuku = $peminjaman->id_buku;
        $this->idStaff = $peminjaman->id_staff;
        $this->alamatPengiriman = $peminjaman->alamat_pengiriman;
        $this->catatanPengiriman = $peminjaman->catatan_pengiriman;
        $this->tglPeminjamanDiinginkan = $peminjaman->tgl_peminjaman_diinginkan ? $peminjaman->tgl_peminjaman_diinginkan->format('Y-m-d') : null;
        $this->existingBuktiPengiriman = $peminjaman->bukti_pengiriman;
        $this->tglDikirim = $peminjaman->tgl_dikirim ? $peminjaman->tgl_dikirim->format('Y-m-d') : null;
        $this->tglKembaliRencana = $peminjaman->tgl_kembali_rencana ? $peminjaman->tgl_kembali_rencana->format('Y-m-d') : null;
        $this->tglKembaliAktual = $peminjaman->tgl_kembali_aktual ? $peminjaman->tgl_kembali_aktual->format('Y-m-d') : null;
        $this->peminjamanStatus = $peminjaman->status;
        $this->metodePengiriman = $peminjaman->metode_pengiriman;
        $this->totalDenda = $peminjaman->total_denda;
        $this->existingBuktiPembayaranDenda = $peminjaman->bukti_pembayaran_denda;
        $this->alasanPenolakan = $peminjaman->alasan_penolakan;
        $this->nomorResi = $peminjaman->nomor_resi;
        
        $this->isModalOpen = true;
    }

    // Open detail modal for a peminjaman
    public function viewPeminjamanDetails($peminjamanId)
    {
        $this->selectedPeminjaman = Peminjaman::with(['user', 'buku', 'staff'])->findOrFail($peminjamanId);
        $this->isDetailModalOpen = true;
    }

    // Close the form modal
    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    // Close the detail modal
    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->selectedPeminjaman = null;
    }

    // Save the peminjaman (create or update)
    public function savePeminjaman()
    {
        $this->validate();
        
        // Creating or updating peminjaman
        $peminjamanData = [
            'id_user' => $this->idUser,
            'id_buku' => $this->idBuku,
            'id_staff' => auth()->id(), // Set current admin as staff
            'alamat_pengiriman' => $this->alamatPengiriman,
            'catatan_pengiriman' => $this->catatanPengiriman,
            'tgl_peminjaman_diinginkan' => $this->tglPeminjamanDiinginkan,
            'tgl_kembali_rencana' => $this->tglKembaliRencana,
            'status' => $this->peminjamanStatus,
            'metode_pengiriman' => $this->metodePengiriman,
        ];
        
        // Add conditional fields based on status
        if ($this->peminjamanStatus === 'DITOLAK') {
            $peminjamanData['alasan_penolakan'] = $this->alasanPenolakan;
        }
        
        if ($this->peminjamanStatus === 'DIKIRIM') {
            $peminjamanData['tgl_dikirim'] = $this->tglDikirim;
            $peminjamanData['nomor_resi'] = $this->nomorResi;
        }
        
        if ($this->peminjamanStatus === 'TERLAMBAT' || $this->peminjamanStatus === 'DIKEMBALIKAN') {
            $peminjamanData['total_denda'] = $this->totalDenda;
        }
        
        if ($this->peminjamanStatus === 'DIKEMBALIKAN') {
            $peminjamanData['tgl_kembali_aktual'] = $this->tglKembaliAktual;
            
            // Increment book stock when returned
            $buku = Buku::find($this->idBuku);
            if ($buku) {
                $buku->increment('stock');
            }
        }
        
        // Handle bukti pengiriman upload
        if ($this->buktiPengiriman) {
            // Delete existing bukti pengiriman if updating
            if ($this->peminjamanId && $this->existingBuktiPengiriman) {
                Storage::delete('public/' . $this->existingBuktiPengiriman);
            }
            
            // Store the new image
            $imagePath = $this->buktiPengiriman->store('peminjaman/bukti-pengiriman', 'public');
            $peminjamanData['bukti_pengiriman'] = $imagePath;
        }
        
        // Handle bukti pembayaran denda upload
        if ($this->buktiPembayaranDenda) {
            // Delete existing bukti pembayaran denda if updating
            if ($this->peminjamanId && $this->existingBuktiPembayaranDenda) {
                Storage::delete('public/' . $this->existingBuktiPembayaranDenda);
            }
            
            // Store the new image
            $imagePath = $this->buktiPembayaranDenda->store('peminjaman/bukti-denda', 'public');
            $peminjamanData['bukti_pembayaran_denda'] = $imagePath;
        }
        
        if ($this->peminjamanId) {
            // Update existing peminjaman
            $peminjaman = Peminjaman::findOrFail($this->peminjamanId);
            $oldStatus = $peminjaman->status;
            $peminjaman->update($peminjamanData);
            
            // If status changed, trigger event
            if ($oldStatus !== $this->peminjamanStatus) {
                event(new PeminjamanStatusChanged($peminjaman));
            }
            
            $message = 'Peminjaman berhasil diperbarui!';
        } else {
            // Create new peminjaman
            $peminjaman = Peminjaman::create($peminjamanData);
            
            // Decrement book stock when creating new peminjaman
            $buku = Buku::find($this->idBuku);
            if ($buku) {
                $buku->decrement('stock');
            }
            
            // Trigger event for new peminjaman
            event(new PeminjamanStatusChanged($peminjaman));
            
            $message = 'Peminjaman berhasil ditambahkan!';
        }
        
        $this->resetForm();
        $this->isModalOpen = false;
        session()->flash('alert', [
            'type' => 'success',
            'message' => $message
        ]);
    }

    // Update peminjaman status
    public function updateStatus($peminjamanId, $newStatus)
    {
        $peminjaman = Peminjaman::findOrFail($peminjamanId);
        $oldStatus = $peminjaman->status;
        
        // Update status
        $peminjaman->status = $newStatus;
        
        // Additional logic based on status
        if ($newStatus === 'DIKIRIM') {
            $peminjaman->tgl_dikirim = now();
        } elseif ($newStatus === 'DIKEMBALIKAN') {
            $peminjaman->tgl_kembali_aktual = now();
            
            // Calculate denda if returned late
            if ($peminjaman->tgl_kembali_rencana && now()->gt($peminjaman->tgl_kembali_rencana)) {
                $daysLate = now()->diffInDays($peminjaman->tgl_kembali_rencana);
                $buku = Buku::find($peminjaman->id_buku);
                if ($buku) {
                    $peminjaman->total_denda = $daysLate * $buku->denda_harian;
                }
            }
            
            // Increment book stock when returned
            $buku = Buku::find($peminjaman->id_buku);
            if ($buku) {
                $buku->increment('stock');
            }
        }
        
        $peminjaman->save();
        
        // If status changed, trigger event
        if ($oldStatus !== $newStatus) {
            event(new PeminjamanStatusChanged($peminjaman));
        }
        
        session()->flash('alert', [
            'type' => 'success',
            'message' => "Status peminjaman berhasil diubah menjadi {$newStatus}!"
        ]);
    }

    // Confirm peminjaman deletion 
    public function confirmPeminjamanDeletion($peminjamanId)
    {
        $this->peminjamanIdToDelete = $peminjamanId;
        $this->confirmingPeminjamanDeletion = true;
    }

    // Delete the peminjaman
    public function deletePeminjaman($peminjamanId = null)
    {
        $idToDelete = $peminjamanId ?? $this->peminjamanIdToDelete;
        $peminjaman = Peminjaman::findOrFail($idToDelete);
        
        // Return book to stock if not returned yet
        if ($peminjaman->status !== 'DIKEMBALIKAN') {
            $buku = Buku::find($peminjaman->id_buku);
            if ($buku) {
                $buku->increment('stock');
            }
        }
        
        // Delete bukti pengiriman if exists
        if ($peminjaman->bukti_pengiriman) {
            Storage::delete('public/' . $peminjaman->bukti_pengiriman);
        }
        
        // Delete bukti pembayaran denda if exists
        if ($peminjaman->bukti_pembayaran_denda) {
            Storage::delete('public/' . $peminjaman->bukti_pembayaran_denda);
        }
        
        $peminjaman->delete();
        
        $this->confirmingPeminjamanDeletion = false;
        $this->peminjamanIdToDelete = null;
        
        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Peminjaman berhasil dihapus!'
        ]);
    }

    // Reset form fields
    private function resetForm()
    {
        $this->peminjamanId = null;
        $this->idUser = '';
        $this->idBuku = '';
        $this->idStaff = '';
        $this->alamatPengiriman = '';
        $this->catatanPengiriman = '';
        $this->tglPeminjamanDiinginkan = now()->format('Y-m-d');
        $this->buktiPengiriman = null;
        $this->existingBuktiPengiriman = null;
        $this->tglDikirim = null;
        $this->tglKembaliRencana = now()->addDays(7)->format('Y-m-d');
        $this->tglKembaliAktual = null;
        $this->peminjamanStatus = 'PENDING';
        $this->metodePengiriman = 'KURIR';
        $this->totalDenda = 0;
        $this->buktiPembayaranDenda = null;
        $this->existingBuktiPembayaranDenda = null;
        $this->alasanPenolakan = '';
        $this->nomorResi = '';
    }
} 