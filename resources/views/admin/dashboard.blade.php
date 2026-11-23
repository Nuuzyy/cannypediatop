<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Perpus Digital</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
        body { background: linear-gradient(135deg, #e2e8f0 0%, #dbeafe 100%); min-height: 100vh; color: #1e293b; padding-bottom: 50px; }
        .navbar { background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); color: white; padding: 16px 40px; display: flex; justify-content: space-between; align-items: center; }
        .brand { font-size: 20px; font-weight: 700; }
        .nav-right { display: flex; align-items: center; gap: 16px; }
        .role-badge { background: #0284c7; padding: 6px 14px; border-radius: 50px; font-size: 13px; font-weight: 600; }
        .btn-logout { background: #ef4444; color: white; border: none; padding: 9px 18px; border-radius: 10px; font-weight: 600; cursor: pointer; }
        .container { max-width: 1100px; margin: 36px auto; padding: 0 20px; }
        .welcome-card { background: linear-gradient(145deg, #1e3a8a 0%, #0369a1 100%); color: white; border-radius: 24px; padding: 36px 40px; margin-bottom: 28px; }
        .welcome-card h1 { font-size: 28px; margin-bottom: 8px; }
        .alert-success { background: #dcfce7; color: #166534; padding: 14px 18px; border-radius: 14px; font-size: 14px; margin-bottom: 24px; }
        .alert-danger { background: #fee2e2; color: #991b1b; padding: 14px 18px; border-radius: 14px; font-size: 14px; margin-bottom: 24px; }
        .grid-menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 22px; margin-bottom: 32px; }
        .menu-card { background: #fff; border-radius: 20px; padding: 24px; border: 2px solid transparent; cursor: pointer; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08); }
        .menu-card.active { border-color: #1d4ed8; background: #eff6ff; }
        .menu-card h3 { font-size: 17px; margin-bottom: 6px; }
        .menu-card p { font-size: 13.5px; color: #64748b; }
        .tab-section { display: none; }
        .tab-section.active { display: block; }
        .panel-card { background: #fff; border-radius: 20px; padding: 28px; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08); margin-bottom: 24px; overflow-x: auto; }
        .panel-card h2 { font-size: 19px; margin-bottom: 18px; }
        .form-grid { display: grid; grid-template-columns: 2fr 2fr 1fr 1fr; gap: 14px; }
        input { width: 100%; padding: 12px 14px; border: 1.5px solid #cbd5e1; border-radius: 12px; font-size: 14px; background: #f8fafc; }
        .btn-add { background: #1d4ed8; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; padding: 12px; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        th { background: #f8fafc; color: #475569; }
        .stock-badge { background: #eff6ff; color: #1d4ed8; padding: 4px 12px; border-radius: 50px; font-size: 12.5px; font-weight: 600; }
        .status-pill { padding: 5px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; }
        .status-dipinjam { background: #fef3c7; color: #b45309; }
        .status-dikembalikan { background: #dcfce7; color: #15803d; }
        .btn-confirm { background: #10b981; color: white; border: none; padding: 8px 14px; border-radius: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer; }
        .btn-delete { background: #ef4444; color: white; border: none; padding: 7px 14px; border-radius: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer; }
        .btn-delete:hover { background: #dc2626; }
        @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="brand">Admin Panel Perpus</div>
    <div class="nav-right">
        <span class="role-badge">Administrator</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</nav>

<div class="container">
    <div class="welcome-card">
        <h1>Halo, Administrator {{ Auth::user()->name }}!</h1>
        <p>Pilih menu di bawah untuk menambah koleksi buku baru atau mengonfirmasi pengembalian buku anggota.</p>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <div class="grid-menu">
        <div class="menu-card active" id="btn-buku" onclick="bukaTab('buku')">
            <h3>Kelola Koleksi Buku</h3>
            <p>Total {{ $books->count() }} judul buku terdaftar di katalog.</p>
        </div>

        <div class="menu-card" id="btn-pinjam" onclick="bukaTab('pinjam')">
            <h3>Transaksi Peminjaman</h3>
            <p>{{ $borrowings->where('status', 'dipinjam')->count() }} buku sedang dipinjam anggota.</p>
        </div>

        <div class="menu-card" style="cursor: default;">
            <h3>Akun Admin</h3>
            <p>Email: <strong>{{ Auth::user()->email }}</strong></p>
        </div>
    </div>

    <div id="tab-buku" class="tab-section active">
        <div class="panel-card">
            <h2>Tambah Koleksi Buku Baru</h2>
            <form method="POST" action="{{ route('admin.books.store') }}" class="form-grid">
                @csrf
                <input type="text" name="judul" placeholder="Judul Buku" required>
                <input type="text" name="penulis" placeholder="Nama Penulis" required>
                <input type="number" name="stok" placeholder="Stok" min="1" required>
                <button type="submit" class="btn-add">Simpan Buku</button>
            </form>
        </div>

        <div class="panel-card">
            <h2>Daftar Koleksi Buku</h2>
            @if($books->isEmpty())
                <p style="text-align: center; color: #64748b;">Belum ada koleksi buku.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th>Stok Tersedia</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $index => $book)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $book->judul }}</strong></td>
                            <td>{{ $book->penulis }}</td>
                            <td><span class="stock-badge">{{ $book->stok }} Eksemplar</span></td>
                            <td>
                                <form method="POST" action="{{ route('admin.books.destroy', $book->id) }}" onsubmit="return confirm('Yakin ingin menghapus buku {{ $book->judul }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div id="tab-pinjam" class="tab-section">
        <div class="panel-card">
            <h2>Daftar Peminjaman Buku Anggota</h2>
            @if($borrowings->isEmpty())
                <p style="text-align: center; color: #64748b;">Belum ada transaksi peminjaman.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Judul Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrowings as $b)
                        <tr>
                            <td><strong>{{ $b->user->name }}</strong><br><small style="color:#64748b">{{ $b->user->email }}</small></td>
                            <td>{{ $b->book->judul }}</td>
                            <td>{{ \Carbon\Carbon::parse($b->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>
                                @if($b->status === 'dipinjam')
                                    <span class="status-pill status-dipinjam">Sedang Dipinjam</span>
                                @else
                                    <span class="status-pill status-dikembalikan">Dikembalikan</span>
                                @endif
                            </td>
                            <td>
                                @if($b->status === 'dipinjam')
                                    <form method="POST" action="{{ route('admin.kembalikan', $b->id) }}">
                                        @csrf
                                        <button type="submit" class="btn-confirm">Konfirmasi Kembali</button>
                                    </form>
                                @else
                                    <span style="color: #64748b; font-size: 13px;">Selesai ({{ \Carbon\Carbon::parse($b->tanggal_kembali)->format('d/m/Y') }})</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<script>
    function bukaTab(namaTab) {
        document.getElementById('tab-buku').classList.remove('active');
        document.getElementById('tab-pinjam').classList.remove('active');
        document.getElementById('btn-buku').classList.remove('active');
        document.getElementById('btn-pinjam').classList.remove('active');

        document.getElementById('tab-' + namaTab).classList.add('active');
        document.getElementById('btn-' + namaTab).classList.add('active');
    }
</script>

</body>
</html>
