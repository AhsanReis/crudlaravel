@extends('layout.master')
@section('page_title', 'Skripsi')

@section('content')
    <form action="{{ route('skripsi.insert') }}" method="post">
        @csrf
        <div class="wrapper-skripsi">
            <div class="form-group form-input">
                <label for="nim" class="nim">NIM</label>
                <input type="text" placeholder="Masukkan NIM Anda" name="nim" class="input-form" id="nim">
                <label for="nama" class="nama">Nama</label>
                <input type="text" placeholder="Masukkan Nama Anda" name="nama" id="nama" class="input-form">
            </div>
            <div class="form-group form-judul">
                <label for="judul" class="judul">Judul</label>
                <input type="text" name="judul" placeholder="Masukkan Judul Anda" class="input-form judul" id="judul">
            </div>
            <div class="form-group form-btn">
                <button class="cek" name="judul-cek">Cek Judul</button>
                <input type="submit" value="Ajukan" name="judul-masuk" id="btn-input">
            </div>
        </div>
    </form>
    <table>
        <thead>
            <tr>
                <th style="height: 28px; width: 38px;">No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Judul Skripsi</th>
                <th style="height: 28px; width: 38px;"> % </th>
            </tr>
        </thead>
        <tbody>
            {{-- menampilkan hasil apabila ada plagiarisme --}}
           {{-- @foreach ($data as $item) 
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
           @endforeach  --}}
        </tbody>
    </table>
@endsection