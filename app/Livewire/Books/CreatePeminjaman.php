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
    
    public $minDatePinjam;
    public $maxDatePinjam;
    public $minDateKembali;
    public $maxDateKembali;

    protected $rules = [
        'alamat_pengiriman' => 'required|string|min:10',
        'catatan_pengiriman' => 'nullable|string',
        'tgl_peminjaman' => 'required|date|after_or_equal:minDatePinjam|before_or_equal:maxDatePinjam',
        'tgl_pengembalian' => 'required|date|after:tgl_peminjaman|before_or_equal:maxDateKembali',
    ];

    protected $messages = [
        'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi',
        'alamat_pengiriman.min' => 'Alamat pengiriman minimal 10 karakter',
        'tgl_peminjaman.required' => 'Tanggal peminjaman wajib diisi',
        'tgl_peminjaman.after_or_equal' => 'Tanggal peminjaman minimal hari ini',
        'tgl_peminjaman.before_or_equal' => 'Tanggal peminjaman maksimal 3 hari dari sekarang',
        'tgl_pengembalian.required' => 'Tanggal pengembalian wajib diisi',
        'tgl_pengembalian.after' => 'Tanggal pengembalian minimal 1 hari setelah tanggal pinjam',
        'tgl_pengembalian.before_or_equal' => 'Durasi peminjaman maksimal 7 hari',
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
            
            // Set tanggal minimal dan maksimal peminjaman
            $this->minDatePinjam = now()->format('Y-m-d');
            $this->maxDatePinjam = now()->addDays(3)->format('Y-m-d');
            
            // Set default tanggal peminjaman ke hari ini
            $this->tgl_peminjaman = $this->minDatePinjam;
            
            // Update tanggal pengembalian ketika mount
            $this->updateDateKembali();

        } catch (\Exception $e) {
            return redirect()->route('buku')->with('error', 'Link peminjaman tidak valid');
        }
    }

    public function updatedTglPeminjaman()
    {
        if ($this->tgl_peminjaman) {
            // Update range tanggal pengembalian berdasarkan tanggal peminjaman yang dipilih
            $this->minDateKembali = Carbon::parse($this->tgl_peminjaman)->addDay()->format('Y-m-d');
            $this->maxDateKembali = Carbon::parse($this->tgl_peminjaman)->addDays(7)->format('Y-m-d');
            
            // Set default tanggal pengembalian ke minimal date kembali yang baru
            $this->tgl_pengembalian = $this->minDateKembali;
        } else {
            $this->minDateKembali = null;
            $this->maxDateKembali = null;
            $this->tgl_pengembalian = null;
        }
    }

    private function updateDateKembali()
    {
        if ($this->tgl_peminjaman) {
            $this->minDateKembali = Carbon::parse($this->tgl_peminjaman)->addDay()->format('Y-m-d');
            $this->maxDateKembali = Carbon::parse($this->tgl_peminjaman)->addDays(7)->format('Y-m-d');
            
            // Hanya set default jika belum ada tanggal pengembalian
            if (!$this->tgl_pengembalian) {
                $this->tgl_pengembalian = $this->minDateKembali;
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