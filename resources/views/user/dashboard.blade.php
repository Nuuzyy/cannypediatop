<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Anggota</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
        body { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); min-height: 100vh; color: #1e293b; padding-bottom: 50px; }
        .navbar { background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: white; padding: 16px 40px; display: flex; justify-content: space-between; align-items: center; }
        .brand { font-size: 20px; font-weight: 700; }
        .nav-right { display: flex; align-items: center; gap: 16px; }
        .role-badge { background: rgba(255, 255, 255, 0.2); padding: 6px 14px; border-radius: 50px; font-size: 13px; font-weight: 600; }
        .btn-logout { background: #ef4444; color: white; border: none; padding: 9px 18px; border-radius: 10px; font-weight: 600; cursor: pointer; }
        .container { max-width: 1100px; margin: 36px auto; padding: 0 20px; }
        .welcome-card { background: linear-gradient(145deg, #1e40af 0%, #0284c7 100%); color: white; border-radius: 24px; padding: 36px 40px; margin-bottom: 28px; }
        .welcome-card h1 { font-size: 28px; margin-bottom: 8px; }
        .alert { padding: 14px 18px; border-radius: 14px; font-size: 14px; margin-bottom: 24px; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-danger { background: #fee2e2; color: #991b1b; }
        .grid-menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 22px; margin-bottom: 32px; }
        .menu-card { background: #fff; border-radius: 20px; padding: 24px; border: 2px solid transparent; cursor: pointer; box-shadow: 0 10px 25px -5px rgba(3, 105, 161, 0.08); }
        .menu-card.active { border-color: #0284c7; background: #f0f9ff; }
        .menu-card h3 { font-size: 17px; margin-bottom: 6px; }
        .menu-card p { font-size: 13.5px; color: #64748b; }
        .tab-section { display: none; }
        .tab-section.active { display: block; }
        .section-title { font-size: 20px; font-weight: 700; margin-bottom: 18px; }
        .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 22px; }
        .book-card { background: #fff; border-radius: 20px; padding: 24px; box-shadow: 0 10px 25px -5px rgba(3, 105, 161, 0.08); display: flex; flex-direction: column; justify-content: space-between; }
        .book-top { display: flex; justify-content: space-between; margin-bottom: 14px; }
        .stock-badge { font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 50px; background: #eff6ff; color: #1d4ed8; }
        .stock-empty { background: #fee2e2; color: #b91c1c; }
        .book-title { font-size: 17px; font-weight: 700; margin-bottom: 6px; }
        .book-author { font-size: 13.5px; color: #64748b; margin-bottom: 20px; }
        .btn-pinjam { width: 100%; padding: 11px; background: #0284c7; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; }
        .btn-disabled { width: 100%; padding: 11px; background: #cbd5e1; color: #64748b; border: none; border-radius: 12px; font-weight: 600; }
        .table-card { background: #fff; border-radius: 20px; padding: 24px; box-shadow: 0 10px 25px -5px rgba(3, 105, 161, 0.08); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        th { background: #f8fafc; color: #475569; }
        .status-pill { padding: 5px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; }
        .status-dipinjam { background: #fef3c7; color: #b45309; }
        .status-dikembalikan { background: #dcfce7; color: #15803d; }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="brand">Perpus Digital</div>
    <div class="nav-right">
        <span class="role-badge">{{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Keluar</button>
        </form>
    </div>
</nav>

<div class="container">
    <div class="welcome-card">
        <h1>Selamat Datang, {{ Auth::user()->name }}!</h1>
        <p>Pilih menu di bawah untuk menjelajahi katalog buku, meminjam buku, atau mengecek status peminjaman.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="grid-menu">
        <div class="menu-card active" id="btn-katalog" onclick="bukaTab('katalog')">
            <h3>Katalog & Peminjaman Buku</h3>
            <p>Tersedia {{ $books->count() }} judul buku untuk dipinjam.</p>
        </div>

        <div class="menu-card" id="btn-status" onclick="bukaTab('status')">
            <h3>Status Peminjaman Saya</h3>
            <p>{{ $myBorrowings->where('status', 'dipinjam')->count() }} buku sedang aktif dipinjam.</p>
        </div>

        <div class="menu-card" style="cursor: default;">
            <h3>Profil Anggota</h3>
            <p>Email: <strong>{{ Auth::user()->email }}</strong></p>
        </div>
    </div>

    <div id="tab-katalog" class="tab-section active">
        <div class="section-title">Katalog Buku Digital</div>

        @if($books->isEmpty())
            <div class="table-card" style="text-align: center; color: #64748b;">
                Belum ada koleksi buku di perpustakaan.
            </div>
        @else
            <div class="book-grid">
                @foreach($books as $book)
                <div class="book-card">
                    <div>
                        <div class="book-top">
                            <span style="font-size: 12px; color: #64748b;">#BUKU-{{ $book->id }}</span>
                            <span class="stock-badge {{ $book->stok < 1 ? 'stock-empty' : '' }}">
                                {{ $book->stok > 0 ? 'Stok: ' . $book->stok : 'Stok Habis' }}
                            </span>
                        </div>
                        <div class="book-title">{{ $book->judul }}</div>
                        <div class="book-author">Penulis: {{ $book->penulis }}</div>
                    </div>

                    @if($book->stok > 0)
                        <form method="POST" action="{{ route('pinjam.buku', $book->id) }}">
                            @csrf
                            <button type="submit" class="btn-pinjam">Pinjam Buku Ini</button>
                        </form>
                    @else
                        <button type="button" class="btn-disabled" disabled>Stok Habis</button>
                    @endif
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <div id="tab-status" class="tab-section">
        <div class="section-title">Status & Riwayat Peminjaman Saya</div>

        <div class="table-card">
            @if($myBorrowings->isEmpty())
                <p style="text-align: center; color: #64748b;">Anda belum pernah meminjam buku.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myBorrowings as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $item->book->judul }}</strong></td>
                            <td>{{ $item->book->penulis }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}</td>
                            <td>
                                @if($item->status === 'dipinjam')
                                    <span class="status-pill status-dipinjam">Sedang Dipinjam</span>
                                @else
                                    <span class="status-pill status-dikembalikan">Sudah Dikembalikan</span>
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
        document.getElementById('tab-katalog').classList.remove('active');
        document.getElementById('tab-status').classList.remove('active');
        document.getElementById('btn-katalog').classList.remove('active');
        document.getElementById('btn-status').classList.remove('active');

        document.getElementById('tab-' + namaTab).classList.add('active');
        document.getElementById('btn-' + namaTab).classList.add('active');
    }
</script>

</body>
</html>

