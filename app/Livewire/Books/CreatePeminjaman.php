<?php

namespace App\Livewire\Books;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreatePeminjaman extends Component
{
    public $book;
    public $token;
    public $alamat_pengiriman;
    public $catatan_pengiriman;
    public $tgl_peminjaman_diinginkan;
    
    public $minDate;
    public $maxDate;

    protected $rules = [
        'alamat_pengiriman' => 'required|string|min:10',
        'catatan_pengiriman' => 'nullable|string',
        'tgl_peminjaman_diinginkan' => 'required|date|after_or_equal:today|before_or_equal:maxDate',
    ];

    protected $messages = [
        'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi',
        'alamat_pengiriman.min' => 'Alamat pengiriman minimal 10 karakter',
        'tgl_peminjaman_diinginkan.required' => 'Tanggal peminjaman wajib diisi',
        'tgl_peminjaman_diinginkan.after_or_equal' => 'Tanggal peminjaman minimal hari ini',
        'tgl_peminjaman_diinginkan.before_or_equal' => 'Tanggal peminjaman maksimal 30 hari dari sekarang',
    ];

    public function mount($token)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Decode token to get book ID and verify expiry
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
            
            // Set min and max dates for peminjaman
            $this->minDate = now()->format('Y-m-d');
            $this->maxDate = now()->addDays(30)->format('Y-m-d');
            $this->tgl_peminjaman_diinginkan = $this->minDate;

        } catch (\Exception $e) {
            return redirect()->route('buku')->with('error', 'Link peminjaman tidak valid');
        }
    }

    public function createPeminjaman()
    {
        $this->validate();

        // Create peminjaman
        $peminjaman = Peminjaman::create([
            'id_user' => Auth::id(),
            'id_buku' => $this->book->id,
            'alamat_pengiriman' => $this->alamat_pengiriman,
            'catatan_pengiriman' => $this->catatan_pengiriman,
            'tgl_peminjaman_diinginkan' => $this->tgl_peminjaman_diinginkan,
            'tgl_kembali_rencana' => Carbon::parse($this->tgl_peminjaman_diinginkan)->addDays(7),
            'status' => Peminjaman::STATUS_MENUNGGU,
            'metode_pengiriman' => 'KURIR'
        ]);

        // Create notifikasi
        Notifikasi::create([
            'id_user' => Auth::id(),
            'id_peminjaman' => $peminjaman->id,
            'message' => "Peminjaman buku {$this->book->judul} berhasil dibuat dan sedang menunggu persetujuan",
            'tipe' => 'PEMINJAMAN_CREATED'
        ]);

        return redirect()->route('peminjaman.detail', ['id' => $peminjaman->id])
            ->with('success', 'Peminjaman berhasil dibuat dan sedang menunggu persetujuan');
    }

    public function render()
    {
        return view('livewire.books.create-peminjaman')->layout('layouts.user');
    }
} 