@extends('auth.layouts')

@section('content')
    <div class="m-3">
        <h1 class="text-center m-3">Data Buku</h1>

        {{-- pesan sukses --}}
        @if (Session::has('pesan'))
            <div class="alert alert-success">{{ Session::get('pesan') }}</div>
        @endif

        <table class="table table-striped" id="table-data">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Harga</th>
                    <th>Tanggal Terbit</th>
                    {{-- <th>Aksi</th> --}}
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($data_buku as $buku)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $buku->judul }}</td>
                        <td>{{ $buku->penulis }}</td>
                        <td>{{ 'Rp. ' . number_format($buku->harga, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($buku->tgl_terbit)->format('d-m-Y') }}</td>
                        {{-- <td>
                            <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-info">Detail</a>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="h4 text-center">Jumlah buku: <span>{{ $jumlah_buku }}</span></p>
        <p class="h4 text-center">Jumlah harga buku: <span>{{ 'Rp. ' . number_format($data_buku->pluck('harga')->sum(), 0, ',', '.') }}</span></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-data').DataTable();
        });
    </script>
@endsection