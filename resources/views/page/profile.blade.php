@extends('layout.master')
@section('page_title', 'Profile')

@section('content')
    <div class="wrapper-profile">
        <div class="profile-tab">
            <table class="satu">
                <td>NIM</td><td>16.11.0420</td>
                <td>Nama</td><td>Ahsani Afif Muhammad Zae</td>

            </table>
        </div>
        <table class="dua">
            <thead>
                <th>Judul Skripsi</th>
                <th>Status</th>
            </thead>
            <tbody>
                {{-- ambil dari database --}}
                <td></td> {{-- nama --}}
                <td></td> {{-- Judul Skripsi --}}
                <td></td> {{-- CRUD --}}
            </tbody>
        </table>
    </div>
@endsection
