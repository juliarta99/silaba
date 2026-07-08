@extends('layouts.app')
@section('title', 'Update Progress — ' . $report->code)

@section('content')

@php
$statusConfig = [
    'pending'               => ['label' => 'Menunggu',           'bg' => 'bg-gray-100',   'text' => 'text-gray-600'],
    'in_progress'           => ['label' => 'Diproses',           'bg' => 'bg-blue-100',   'text' => 'text-blue-700'],
    'waiting_for_materials' => ['label' => 'Menunggu Material',  'bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
    'under_review'          => ['label' => 'Menunggu Verifikasi','bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
    'completed'             => ['label' => 'Selesai',            'bg' => 'bg-green-100',  'text' => 'text-success'],
];
$curStatus = $statusConfig[$report->status] ?? $statusConfig['pending'];
@endphp

<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 pt-6">

        {{-- Back + Heading --}}
        <div class="mb-6">
            <a href="{{ route('employee.field-officer.assignments.show', $report->code) }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-700 mb-3 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 14 14">
                    <path d="M9 3L4 7l5 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali ke Detail Tugas
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Update Progress</h1>
            <p class="text-sm text-gray-500 mt-0.5">Tambahkan update progress untuk tugas {{ $report->code }}</p>
        </div>

        {{-- Info Tugas --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <h2 class="text-sm font-bold text-gray-900 mb-4">Informasi Tugas</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Nomor Tiket</p>
                    <p class="text-sm font-bold text-gray-900 font-mono">{{ $report->code }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Status Saat Ini</p>
                    <span class="inline-flex text-xs font-semibold px-2.5 py-1 rounded-full
                                 {{ $curStatus['bg'] }} {{ $curStatus['text'] }}">
                        {{ $curStatus['label'] }}
                    </span>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 mb-0.5">Judul Laporan</p>
                    <p class="text-sm font-bold text-gray-900">{{ $report->title }}</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form
            action="{{ route('employee.field-officer.assignments.progress.store', $report->code) }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{
                photoPreview: null,
                photoName: '',
                handlePhoto(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    this.photoName = file.name;
                    const reader = new FileReader();
                    reader.onload = (r) => this.photoPreview = r.target.result;
                    reader.readAsDataURL(file);
                },
                removePhoto() {
                    this.photoPreview = null;
                    this.photoName = '';
                    this.$refs.photoInput.value = '';
                }
            }"
        >
            @csrf

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
                <h2 class="text-sm font-bold text-gray-900 mb-5">Form Update Progress</h2>

                <div class="space-y-5">

                    {{-- Update Status --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">
                            Update Status <span class="text-error">*</span>
                        </label>
                        <select name="status"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                       text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-1
                                       focus:ring-primary-500 outline-none transition-all"
                                required>
                            <option value="" disabled selected>Pilih status yang sesuai dengan kondisi pekerjaan saat ini</option>
                            @foreach ($statusOptions as $val => $label)
                            <option value="{{ $val }}" {{ old('status') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        <p class="mt-1.5 text-xs text-gray-400">Pilih status yang sesuai dengan kondisi pekerjaan saat ini</p>
                        @error('status') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Judul Progress --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">
                            Judul Progress <span class="text-error">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               placeholder="Contoh: Survey lokasi telah dilakukan"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                      text-gray-900 placeholder-gray-400 focus:bg-white focus:border-primary-500
                                      focus:ring-1 focus:ring-primary-500 outline-none transition-all"
                               required>
                        @error('title') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">
                            Deskripsi Progress <span class="text-error">*</span>
                        </label>
                        <textarea name="description" rows="5"
                                  placeholder="Jelaskan secara detail progress yang telah dilakukan, temuan di lapangan, dan rencana selanjutnya..."
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                         text-gray-900 placeholder-gray-400 resize-none focus:bg-white
                                         focus:border-primary-500 focus:ring-1 focus:ring-primary-500
                                         outline-none transition-all"
                                  required>{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Foto Dokumentasi --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">
                            Foto Dokumentasi
                            <span class="text-xs font-normal text-gray-400 ml-1">(Opsional)</span>
                        </label>

                        {{-- Preview --}}
                        <div x-show="photoPreview" class="mb-3" style="display:none;">
                            <div class="relative inline-block">
                                <img :src="photoPreview" class="w-32 h-24 object-cover rounded-xl border border-gray-200" alt="">
                                <button type="button" @click="removePhoto()"
                                        class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-error text-white
                                               flex items-center justify-center shadow-sm hover:bg-red-700 transition-colors">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                        <path d="M3 3l6 6M9 3l-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 mt-1" x-text="photoName"></p>
                        </div>

                        <label x-show="!photoPreview"
                               class="flex flex-col items-center justify-center gap-2 py-8 rounded-xl
                                      border-2 border-dashed border-gray-200 bg-gray-10 hover:border-primary-300
                                      hover:bg-primary-50 cursor-pointer transition-colors">
                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24">
                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2zm8-12h.01"
                                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p class="text-sm text-gray-600 font-medium">Klik untuk upload foto</p>
                            <p class="text-xs text-gray-400">PNG, JPG atau JPEG (Maks. 5MB per file)</p>
                            <input type="file" name="photo" x-ref="photoInput"
                                   accept="image/png,image/jpg,image/jpeg"
                                   class="hidden" @@change="handlePhoto($event)">
                        </label>
                        @error('photo') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Estimasi Penyelesaian --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">
                            Estimasi Penyelesaian
                            <span class="text-xs font-normal text-gray-400 ml-1">(Opsional)</span>
                        </label>
                        <input type="datetime-local" name="estimated_completion"
                               value="{{ old('estimated_completion') }}"
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                      text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-1
                                      focus:ring-primary-500 outline-none transition-all">
                        <p class="mt-1.5 text-xs text-gray-400">Perkiraan kapan pekerjaan akan selesai</p>
                        @error('estimated_completion') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Catatan Internal --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">
                            Catatan Internal
                            <span class="text-xs font-normal text-gray-400 ml-1">(Opsional)</span>
                        </label>
                        <textarea name="notes" rows="3"
                                  placeholder="Catatan khusus untuk internal OPD (tidak akan ditampilkan ke pelapor)..."
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                         text-gray-900 placeholder-gray-400 resize-none focus:bg-white
                                         focus:border-primary-500 focus:ring-1 focus:ring-primary-500
                                         outline-none transition-all">{{ old('notes') }}</textarea>
                        <p class="mt-1.5 text-xs text-gray-400">Catatan ini hanya untuk internal dan tidak akan dilihat oleh pelapor</p>
                        @error('notes') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            {{-- Info Penting --}}
            <div class="flex items-start gap-3 px-4 py-4 rounded-2xl bg-blue-50 border border-blue-100 mb-5">
                <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24">
                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-blue-800 mb-0.5">Informasi Penting</p>
                    <p class="text-xs text-blue-700 leading-relaxed">
                        Update progress ini akan dikirimkan ke pelapor melalui WhatsApp.
                        Pastikan informasi yang Anda berikan akurat dan mudah dipahami.
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3">
                <a href="{{ route('employee.field-officer.assignments.show', $report->code) }}"
                   class="px-5 py-3 rounded-xl border border-gray-200 text-gray-700 text-sm
                          font-semibold hover:bg-gray-10 transition-colors text-center">
                    Batal
                </a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl
                               bg-primary-500 hover:bg-primary-700 text-white text-sm font-bold
                               transition-colors active:scale-[.98]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                        <path d="M11.5 2.5a2.121 2.121 0 013 3L5 15H1v-4L11.5 2.5z"
                              stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Simpan Update Progress
                </button>
            </div>

        </form>
    </div>
</div>

<x-employee.bottom-nav active="assignments" :badge="0" />

@endsection