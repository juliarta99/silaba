@extends('layouts.app')

@section('title', 'Tambah Pengguna — SILABU')

@section('content')
{{-- Form tambah user baru (hanya super_admin: untuk tambah admin) --}}
<div class="max-w-7xl mx-auto px-5 sm:px-6 py-8">
    <h1 class="text-2xl font-bold text-gray-900">Tambah Pengguna</h1>
    <p class="text-sm text-gray-500 mt-1">Form tambah user baru (hanya super_admin: untuk tambah admin)</p>
</div>
@endsection
