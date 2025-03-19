<?php

namespace App\Livewire\Books;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CreatePeminjaman extends Component
{
    public $book;
    public $token;
    public $alamat_pengiriman;
    public $catatan_pengiriman;
    public $tgl_peminjaman;
    public $tgl_pengembalian;
    
    public $maxReturnDate;
    public $minReturnDate;

    protected $rules = [
        'alamat_pengiriman' => 'required|string|min:10',
        'catatan_pengiriman' => 'nullable|string',
        'tgl_peminjaman' => [
            'required',
            'date',
            'after_or_equal:today',
            'before_or_equal:maxDatePinjam'
        ],
        'tgl_pengembalian' => [
            'required',
            'date',
            'after:tgl_peminjaman',
            'before_or_equal:maxReturnDate'
        ],
    ];

    protected $messages = [
        'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi',
        'alamat_pengiriman.min' => 'Alamat pengiriman minimal 10 karakter',
        'tgl_peminjaman.required' => 'Tanggal peminjaman wajib diisi',
        'tgl_peminjaman.after_or_equal' => 'Tanggal peminjaman minimal hari ini',
        'tgl_peminjaman.before_or_equal' => 'Tanggal peminjaman maksimal 3 hari dari sekarang',
        'tgl_pengembalian.required' => 'Tanggal pengembalian wajib diisi',
        'tgl_pengembalian.after' => 'Minimal peminjaman adalah 1 hari',
        'tgl_pengembalian.before_or_equal' => 'Maksimal peminjaman adalah 7 hari',
    ];

    public function mount($token)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            $decoded = json_decode(base64_decode($token), true);
            
            if (!$decoded || 
                !isset($decoded['book_id']) || 
                !isset($decoded['expiry']) || 
                Carbon::parse($decoded['expiry'])->isPast()
            ) {
                return redirect()->route('buku')->with('error', 'Link peminjaman tidak valid atau sudah kadaluarsa');
            }

            $this->book = Buku::findOrFail($decoded['book_id']);
            $this->token = $token;
            
            // Set tanggal maksimal peminjaman (3 hari dari sekarang)
            $this->maxDatePinjam = now()->addDays(3)->format('Y-m-d');
            
            // Set default tanggal peminjaman ke hari ini
            $this->tgl_peminjaman = now()->format('Y-m-d');
            
            // Update tanggal pengembalian ketika mount
            $this->updateDateKembali();

        } catch (\Exception $e) {
            return redirect()->route('buku')->with('error', 'Link peminjaman tidak valid');
        }
    }

    public function updatedTglPeminjaman($value)
    {
        if ($value) {
            // Set range tanggal pengembalian berdasarkan tanggal peminjaman yang dipilih
            $this->maxReturnDate = Carbon::parse($value)->addDays(7)->format('Y-m-d');
            $this->minReturnDate = Carbon::parse($value)->addDay()->format('Y-m-d');
            
            // Reset tanggal pengembalian jika sudah tidak valid
            if ($this->tgl_pengembalian) {
                $tglKembali = Carbon::parse($this->tgl_pengembalian);
                if ($tglKembali->lte($value) || $tglKembali->gt($this->maxReturnDate)) {
                    $this->tgl_pengembalian = null;
                }
            }
        } else {
            $this->maxReturnDate = null;
            $this->minReturnDate = null;
            $this->tgl_pengembalian = null;
        }
    }

    private function updateDateKembali()
    {
        if ($this->tgl_peminjaman) {
            $this->maxReturnDate = Carbon::parse($this->tgl_peminjaman)->addDays(7)->format('Y-m-d');
            $this->minReturnDate = Carbon::parse($this->tgl_peminjaman)->addDay()->format('Y-m-d');
            
            // Hanya set default jika belum ada tanggal pengembalian
            if (!$this->tgl_pengembalian) {
                $this->tgl_pengembalian = $this->minReturnDate;
            }
        }
    }

    public function createPeminjaman()
    {
        $this->validate();

        $this->book->decrement('stock');

        $peminjaman = Peminjaman::create([
            'id_user' => Auth::id(),
            'id_buku' => $this->book->id,
            'alamat_pengiriman' => $this->alamat_pengiriman,
            'catatan_pengiriman' => $this->catatan_pengiriman,
            'tgl_peminjaman_diinginkan' => $this->tgl_peminjaman,
            'tgl_kembali_rencana' => $this->tgl_pengembalian,
            'status' => 'PENDING',
            'metode_pengiriman' => 'KURIR'
        ]);

        // Create notifikasi untuk user
        Notifikasi::create([
            'id_user' => Auth::id(),
            'id_peminjaman' => $peminjaman->id,
            'message' => "Peminjaman buku {$this->book->judul} berhasil dibuat dan sedang menunggu persetujuan",
            'tipe' => 'PEMINJAMAN_CREATED'
        ]);

        // Create notifikasi untuk admin
        $admins = User::where('role', 'ADMIN')->get();
        foreach ($admins as $admin) {
            Notifikasi::create([
                'id_user' => $admin->id,
                'id_peminjaman' => $peminjaman->id,
                'message' => "Ada permintaan peminjaman buku baru dari " . Auth::user()->name,
                'tipe' => 'PEMINJAMAN_CREATED'
            ]);
        }

        return redirect()->route('peminjaman.detail', ['id' => $peminjaman->id])
            ->with('success', 'Peminjaman berhasil dibuat dan sedang menunggu persetujuan');
    }

    public function render()
    {
        return view('livewire.books.create-peminjaman')->layout('layouts.user');
    }
} 