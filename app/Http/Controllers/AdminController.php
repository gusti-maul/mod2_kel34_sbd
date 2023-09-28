<?php

namespace App\Http\Controllers;

// library yang digunakan pada controller ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function create() // fungsi create
    {
        return view('admin.add'); // akan menampilkan halaman 'admin.add' yang ada pada resources->views->admin->add.blade.php
    }

    // public function store the value to a table
    public function store(Request $request) // fungsi untuk menyimpan data ke dalam database atau store data pada database
    {
        $request->validate([
            'id_admin' => 'required',
            'nama_admin' => 'required',
            'alamat' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]); // fungsi ini akan memvalidasi agar tiap-tiap atribut tidak kosong ketika akan diisi, fungsi ini menggunakan perintah 'required' sehingga kolom tersebut wajib diisi

        DB::insert(
            'INSERT INTO admin(id_admin,nama_admin, alamat, username, password) VALUES (:id_admin, :nama_admin, :alamat, :username, :password)',
            [
                'id_admin' => $request->id_admin,
                'nama_admin' => $request->nama_admin,
                'alamat' => $request->alamat,
                'username' => $request->username,
                'password' => $request->password,
            ]
        ); // fungsi ini akan memasukkan data dari halaman 'admin.add' kedalam database menggunakan perintah 'insert into' yang langsung memasukkan data kedalam tabel 'admin' dengan 'values' yang sudah diisi pada halaman 'admin.add'
        return redirect()->route('admin.index')->with('success', 'Data Admin berhasil disimpan'); // baris ini akan mengembalikan halaman ke halaman 'admin.index' ketika database berhasil disimpan
    }

    // public function show all values from a table
    public function index()
    {
        $datas = DB::select('select * from admin');
        return view('admin.index')->with('datas', $datas);
    } // fungsi ini akan menampilkan halaman 'admin.index' dengan mengambil data dari tabel 'admin' yang ada di database menggunakan perintah 'select'

    // public function edit a row from a table
    public function edit($id)
    {
        $data = DB::table('admin')->where('id_admin', $id)->first();
        return view('admin.edit')->with('data', $data);
    } // fungsi ini akan menampilkan halaman 'admin.edit' dan mengambil data dari 'id_admin' yang dipilih agar dapat mengubah data yang terdapat pada 'id_admin' tersebut

    // public function to update the table value
    public function update($id, Request $request)
    {
        $request->validate([
            'id_admin' => 'required',
            'nama_admin' => 'required',
            'alamat' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]); // fungsi ini akan memvalidasi apakah seluruh kolom telah terisi menggunakan perintah 'required'

        DB::update(
            'UPDATE admin SET id_admin = :id_admin, nama_admin = :nama_admin, alamat = :alamat, username = :username, password = :password WHERE id_admin = :id',
            [
                'id' => $id,
                'id_admin' => $request->id_admin,
                'nama_admin' => $request->nama_admin,
                'alamat' => $request->alamat,
                'username' => $request->username,
                'password' => $request->password,
            ]
        ); // fungsi ini akan meng-update data dari database dengan data baru hasil data yang dimasukkan pada halaman 'admin.edit' sebelumnya

        return redirect()->route('admin.index')->with('success', 'Data Admin berhasil diubah'); // ketika data berhasil di update, maka halaman akan dialihkan ke 'admin.index' kembali
    }

    // public function to delete a row from a table
    public function delete($id)
    {
        DB::delete('DELETE FROM admin WHERE id_admin = :id_admin', ['id_admin' => $id]);
        return redirect()->route('admin.index')->with('success', 'Data Admin berhasil dihapus');
    } // fungsi ini akan menghapus data pada halaman 'admin.index' berdasarkan pada 'id_admin' yang dipilih, fungsi ini sekaligus akan menghapus data pada database karena yang dihapus merupakan primary key sehingga data yang terhubung pada primary key akan terhapus seluruhnya
}
