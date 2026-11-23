<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerpusController extends Controller
{
    // 1. Dashboard Pengguna
    public function userDashboard()
    {
        if (Auth::user()->role !== 'user') abort(403, 'Akses ditolak.');
        
        $books = Book::latest()->get();
        $myBorrowings = Borrowing::with('book')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.dashboard', compact('books', 'myBorrowings'));
    }

    // 2. Proses Pinjam Buku (Pengguna)
    public function pinjamBuku($bookId)
    {
        if (Auth::user()->role !== 'user') abort(403, 'Akses ditolak.');

        $book = Book::findOrFail($bookId);
        if ($book->stok < 1) {
            return back()->with('error', 'Maaf, stok buku ini sedang habis.');
        }

        $sedangPinjam = Borrowing::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sedangPinjam) {
            return back()->with('error', 'Anda masih meminjam buku ini dan belum mengembalikannya.');
        }

        Borrowing::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'tanggal_pinjam' => now(),
            'status' => 'dipinjam',
        ]);

        $book->decrement('stok');

        return back()->with('success', 'Berhasil meminjam buku "' . $book->judul . '"!');
    }

    // 3. Dashboard Admin
    public function adminDashboard()
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Akses khusus Admin.');

        $books = Book::latest()->get();
        $borrowings = Borrowing::with(['user', 'book'])->latest()->get();

        return view('admin.dashboard', compact('books', 'borrowings'));
    }

    // 4. Tambah Buku Baru (Admin)
    public function storeBook(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Akses khusus Admin.');

        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'stok' => 'required|integer|min:1',
        ]);

        Book::create($request->all());

        return back()->with('success', 'Buku baru berhasil ditambahkan ke katalog!');
    }

    // 5. Konfirmasi Pengembalian Buku (Admin)
    public function kembalikanBuku($borrowingId)
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Akses khusus Admin.');

        $borrowing = Borrowing::findOrFail($borrowingId);

        if ($borrowing->status === 'dipinjam') {
            $borrowing->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now(),
            ]);
            $borrowing->book->increment('stok');
        }

        return back()->with('success', 'Status peminjaman diperbarui menjadi Dikembalikan.');
    }

        // 6. Hapus Buku (Admin)
    public function destroyBook($bookId)
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Akses khusus Admin.');

        $book = Book::findOrFail($bookId);

        // Cegah penghapusan jika buku masih dipinjam anggota
        $sedangDipinjam = Borrowing::where('book_id', $book->id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sedangDipinjam) {
            return back()->with('error', 'Buku "' . $book->judul . '" tidak bisa dihapus karena masih dipinjam anggota.');
        }

        $book->delete();

        return back()->with('success', 'Buku "' . $book->judul . '" berhasil dihapus dari katalog.');
    }

}
