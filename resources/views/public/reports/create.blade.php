{{-- resources/views/public/reports/create.blade.php --}}
@extends('layouts.app')

@section('title', auth()->check() ? 'Buat Laporan Baru' : 'Pengajuan Laporan Tamu')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('content')
    <livewire:report.create-report-wizard />
@endsection