<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only([
            'index','search'
        ]);
        $this->middleware('admin')->except([
            'index','search'
        ]);
    }

    public function index()
    {
        $data_buku = Buku::all();
        $jumlah_buku = Buku::count();
        $no = 0;

        if (auth()->check() && auth()->user()->level == 'admin') {
            return view('buku.index', compact('data_buku', 'no', 'jumlah_buku'));
        }

        return view('buku.users', compact('data_buku', 'no', 'jumlah_buku'));
    }

    public function search(Request $request)
    {
        Paginator::useBootstrapFive();
        $batas = 5;
        $cari = $request->kata;
        $data_buku = Buku::where('judul', 'like', "%" . $cari . "%")->orwhere('judul', 'like', "%" . $cari . "%")->paginate($batas);
        $jumlah_buku = Buku::count();
        $no = $batas * ($data_buku->currentPage() - 1);
        return view('buku.index', compact('jumlah_buku', 'data_buku', 'no', 'cari'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'judul' => 'required|string',
                'penulis' => 'required|string|max:30',
                'harga' => 'required|numeric',
                'tgl_terbit' => 'required|date',
                'photo' => 'nullable|image|max:2048',
            ],
            [
                'required' => ':attribute wajib diisi',
                'string' => ':attribute diisi dengan string',
                'numeric' => ':attribute harus diisi dengan angka',
                'date' => ':attribute harus diisi dengan tanggal',
                'max' => ':attribute minimal berisi :max karakter'
            ]
        );

        if ($request->hasFile('photo')) {
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenameSimpan = $filename . '_' . time() . '.' . $extension;
            $request->file('photo')->storeAs('photos', $filenameSimpan);
        }

        $buku = new Buku();
        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->harga = $request->harga;
        $buku->tgl_terbit = $request->tgl_terbit;

        if (isset($filenameSimpan)) {
            $buku->photo = $filenameSimpan;
        }

        $buku->save();

        return response()->json($buku, 201);
    }


    public function destroy($id)
    {
        $buku = Buku::find($id);
        $buku->delete();
        return redirect('/buku');
    }

    public function edit($id)
    {
        $buku = Buku::find($id);
        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'judul' => 'required|string',
                'penulis' => 'required|string|max:30',
                'harga' => 'required|numeric',
                'tgl_terbit' => 'required|date',
            ],
            [
                'required' => ':attribute wajib diisi',
                'string' => ':attribute diisi dengan string',
                'numeric' => ':attribute harus diisi dengan angka',
                'date' => ':attribute harus diisi dengan tanggal',
                'max' => ':attribute minimal berisi :max karakter'
            ]
        );

        if ($request->hasFile('photo')) {
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenameSimpan = $filename . '_' . time() . '.' . $extension;
            // $request->file('photo')->storeAs('public', $filenameSimpan);
            $path = $request->file('photo')->storeAs('photos', $filenameSimpan);
            $this->resizePhoto($filenameSimpan);
        }

        $buku = Buku::find($id);
        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->harga = $request->harga;
        $buku->tgl_terbit = $request->tgl_terbit;
        $buku->ori_image = $filenameSimpan ?? null;
        $buku->square_image= 'square' . $filenameSimpan ?? null;
        $buku->save();
        return redirect()->route('buku.index');
    }

    public function resizePhoto($filename)
    {
        $image_ori = Storage::get('photos/' . $filename);
        $image_square = Image::read($image_ori);
        $image_square->resize(100, 100);
        $image_square->save(Storage::path('photos/' . 'square_' . $filename));
    }

    public function show(Buku $buku)
     {
        $buku = Buku::find($buku->id);
        return view('buku.show', compact('buku'));
     }



    
}
