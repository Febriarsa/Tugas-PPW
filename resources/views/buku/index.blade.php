@extends('auth.layouts')

@section('content')
    <div class="m-3">
        <h1 class="text-center m-3">Data Buku</h1>
        @if (Session::has('pesan'))
            <div class="alert alert-success">{{ Session::get('pesan') }}</div>
        @endif

        @auth
            @if(auth()->check() && auth()->user()->level == 'admin')
            <a href="{{ route('buku.create') }}" class="btn btn-primary float-end">Tambah Buku</a>
            @endif
        @endauth
        <table class="table table-striped" id="table-data">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Harga</th>
                    <th>Tanggal Terbit</th>
                    @auth
                        @if(auth()->check() && auth()->user()->level == 'admin')
                        <th>Aksi</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody id="books-table">
                <!-- Data akan ditambahkan di sini -->
            </tbody>
        </table>
        <p class="h4 text-center">jumlah buku: <span id="jumlah-buku"></span></p>
        <p class="h4 text-center">jumlah harga buku: <span id="jumlah-harga"></span></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-data').DataTable();
            fetchBooks();
        });

        function fetchBooks() {
            fetch('/api/books')
                .then(response => response.json())
                .then(data => {
                    let tableBody = document.getElementById('books-table');
                    tableBody.innerHTML = '';
                    let jumlahBuku = 0;
                    let totalHarga = 0;
                    data.forEach(book => {
                        jumlahBuku++;
                        totalHarga += book.harga;
                        let row = `<tr>
                            <td>${book.id}</td>
                            <td><img src="${book.photo ? 'storage/' + book.photo : 'default.jpg'}" alt="buku" width="100"></td>
                            <td>${book.judul}</td>
                            <td>${book.penulis}</td>
                            <td>Rp. ${new Intl.NumberFormat('id-ID').format(book.harga)}</td>
                            <td>${new Date(book.tgl_terbit).toLocaleDateString('id-ID')}</td>
                            @auth
                                @if(auth()->check() && auth()->user()->level == 'admin')
                                <td class="d-flex">
                                    <form action="{{ route('buku.destroy', book.id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin mau dihapus')" type="submit"
                                            class="btn btn-danger">Hapus</button>
                                    </form>
                                    <a href="{{ route('buku.edit', book.id) }}" class="btn btn-secondary">Edit</a>
                                </td>
                                @endif
                            @endauth
                        </tr>`;
                        tableBody.innerHTML += row;
                    });
                    document.getElementById('jumlah-buku').textContent = jumlahBuku;
                    document.getElementById('jumlah-harga').textContent = totalHarga;
                });
        }
    </script>
@endsection
