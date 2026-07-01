{{--
    resources/views/livewire/create-report-wizard.blade.php

    FIXED: 
    1. Menambahkan wire:ignore pada container peta agar Livewire tidak mendiff/merusak DOM Leaflet.
    2. Menambahkan fungsi pembersihan otomatis (this._map.remove()) jika element peta dihancurkan saat pindah step.
    3. Mempertahankan posisi marker terakhir jika pengguna kembali ke Step 2 dari Step 1.
--}}

<div>

{{-- ══════════════════════════════════════
     Leaflet CSS — inject sekali saja
════════════════════════════════════════ --}}
@if ($step === 2)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endif

{{-- ══════════════════════════════════════
     ALPINE FUNCTION — HARUS di atas x-data
════════════════════════════════════════ --}}
<script>
// Guard: jangan redefinisi jika sudah ada (Livewire re-render)
if (typeof window.reportMap === 'undefined') {
    window.reportMap = function () {
        return {
            locationText: '',
            locating:     false,
            geoError:     null,
            dragging:     false,
            _map:         null,
            _marker:      null,

            DEFAULT_LAT: -8.5833,
            DEFAULT_LNG: 115.1667,

            init() {
                // Inisialisasi map saat step 2 sudah di DOM
                this.$watch('$wire.step', (val) => {
                    if (val === 2) {
                        this.$nextTick(() => this.initMap());
                    }
                });

                // Jika sudah step 2 sejak awal (auth user)
                if (this.$wire.step === 2) {
                    this.$nextTick(() => this.initMap());
                }
            },

            initMap() {
                const mapEl = document.getElementById('report-map');
                if (!mapEl) return;

                // --- FIX 1: Penanganan Peta yang Terbawa di Memory ---
                if (this._map) {
                    // Jika kontainer masih ada di dalam dokumen body, cukup resize biasa
                    if (document.body.contains(this._map._container)) {
                        this._map.invalidateSize();
                        return;
                    } else {
                        // Jika kontainer lama sudah hilang dari DOM (karena ganti step),
                        // wajib dihancurkan/clean-up terlebih dahulu sebelum init ulang.
                        this._map.remove();
                        this._map = null;
                        this._marker = null;
                    }
                }

                // Muat Leaflet JS jika belum ada
                if (typeof L === 'undefined') {
                    this._loadLeafletJS(() => this._buildMap(mapEl));
                } else {
                    this._buildMap(mapEl);
                }
            },

            _loadLeafletJS(callback) {
                const s = document.createElement('script');
                s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                s.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
                s.crossOrigin = '';
                s.onload = callback;
                document.head.appendChild(s);
            },

            _buildMap(mapEl) {
                // --- FIX 2: Restorasi State Koordinat yang Sudah Ada di Livewire ---
                let lat = this.$wire.latitude ? parseFloat(this.$wire.latitude) : this.DEFAULT_LAT;
                let lng = this.$wire.longitude ? parseFloat(this.$wire.longitude) : this.DEFAULT_LNG;

                this._map = L.map(mapEl).setView([lat, lng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>',
                    maxZoom: 19,
                }).addTo(this._map);

                // Pasang kembali marker jika koordinat sudah pernah diisi sebelumnya
                if (this.$wire.latitude && this.$wire.longitude) {
                    this._placeMarker(lat, lng);
                }

                // Klik peta → taruh/pindah marker
                this._map.on('click', (e) => {
                    this._placeMarker(e.latlng.lat, e.latlng.lng);
                    this._syncToLivewire(e.latlng.lat, e.latlng.lng);
                    this._reverseGeocode(e.latlng.lat, e.latlng.lng);
                });
            },

            _placeMarker(lat, lng) {
                if (this._marker) {
                    this._marker.setLatLng([lat, lng]);
                } else {
                    this._marker = L.marker([lat, lng], { draggable: true }).addTo(this._map);
                    this._marker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        this._syncToLivewire(pos.lat, pos.lng);
                        this._reverseGeocode(pos.lat, pos.lng);
                    });
                }
            },

            _syncToLivewire(lat, lng) {
                this.$wire.set('latitude',  parseFloat(lat).toFixed(7));
                this.$wire.set('longitude', parseFloat(lng).toFixed(7));
            },

            async _reverseGeocode(lat, lng) {
                try {
                    const res = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`,
                        { headers: { 'Accept-Language': 'id,en' } }
                    );
                    const data = await res.json();
                    if (data?.display_name) {
                        this.locationText = data.display_name;
                        this.$wire.set('location', data.display_name);
                    }
                } catch (_) { /* tidak fatal */ }
            },

            // ── "Lokasi Saat Ini" button ──────────────────────────
            useCurrentLocation() {
                this.geoError = null;

                if (!navigator.geolocation) {
                    this.geoError = 'Browser Anda tidak mendukung geolokasi.';
                    return;
                }

                this.locating = true;

                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const { latitude: lat, longitude: lng } = pos.coords;
                        this._map.setView([lat, lng], 17);
                        this._placeMarker(lat, lng);
                        this._syncToLivewire(lat, lng);
                        this._reverseGeocode(lat, lng);
                        this.locating  = false;
                        this.geoError  = null;
                    },
                    (err) => {
                        this.locating = false;
                        const msgs = {
                            1: 'Izin lokasi ditolak. Aktifkan izin lokasi di pengaturan browser, atau klik peta secara manual.',
                            2: 'Informasi lokasi tidak tersedia. Silakan klik peta untuk memilih lokasi.',
                            3: 'Permintaan lokasi timeout. Silakan coba lagi.',
                        };
                        this.geoError = msgs[err.code] ?? 'Gagal mendapatkan lokasi. Klik peta untuk memilih manual.';
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            },
        };
    };
}
</script>

{{-- ══════════════════════════════════════
     ROOT DIV — x-data harus setelah
     <script> function terdefinisi
════════════════════════════════════════ --}}
<div
    x-data="reportMap()"
    x-init="init()"
    class="bg-gray-10 min-h-[calc(100vh-68px)] pb-12 pt-32 px-4 sm:px-6"
>
    <div class="max-w-2xl mx-auto">

        {{-- ── Heading ── --}}
        <div class="text-center mb-7">
            @guest
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Pengajuan Laporan Tamu</h1>
                <p class="text-sm text-gray-500 mt-1.5">Laporan untuk wisatawan atau pengunjung tanpa akun</p>
            @endguest
            @auth
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Buat Laporan Baru</h1>
                <p class="text-sm text-gray-500 mt-1.5">Laporkan masalah yang Anda temukan di wilayah Kabupaten Badung</p>
            @endauth
        </div>

        {{-- ── Stepper (GUEST ONLY) ── --}}
        @guest
        <div class="flex items-center gap-3 justify-center mb-7">
            <div class="flex items-center gap-2.5">
                @if ($step > 1)
                    <span class="w-8 h-8 rounded-full bg-success text-white flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                            <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                @else
                    <span class="w-8 h-8 rounded-full bg-primary-500 text-white text-sm font-bold
                                 flex items-center justify-center shrink-0">1</span>
                @endif
                <span class="text-sm font-medium {{ $step === 1 ? 'font-semibold text-gray-900' : 'text-gray-500' }}">
                    Data Pelapor
                </span>
            </div>

            <div class="w-14 sm:w-20 h-px {{ $step > 1 ? 'bg-primary-500' : 'bg-gray-200' }}"></div>

            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-full text-sm font-bold flex items-center justify-center shrink-0
                             {{ $step === 2 ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-400' }}">
                    2
                </span>
                <span class="text-sm font-medium {{ $step === 2 ? 'font-semibold text-gray-900' : 'text-gray-400' }}">
                    Detail Laporan
                </span>
            </div>
        </div>

        {{-- Banner catatan tamu --}}
        <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-blue-50 border border-blue-100 mb-6">
            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm text-blue-700 leading-relaxed">
                <span class="font-semibold">Catatan:</span>
                Sebagai tamu, fitur pelacakan laporan Anda terbatas. Untuk akses penuh, silakan
                <a href="{{ route('register') }}"
                   class="font-semibold underline hover:text-blue-900 transition-colors">daftar akun</a>
                menggunakan NIK.
            </p>
        </div>
        @endguest

        {{-- Banner akun terverifikasi (AUTH) --}}
        @auth
        <div class="flex items-start gap-3 px-5 py-4 rounded-xl bg-green-50 border border-green-100 mb-6">
            <svg class="w-5 h-5 text-success shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm text-green-800 leading-relaxed">
                <span class="font-semibold">Akun Terverifikasi:</span>
                Sebagai warga terverifikasi, Anda mendapatkan pelacakan real-time, notifikasi
                WhatsApp, dan poin reward untuk setiap laporan terverifikasi.
            </p>
        </div>
        @endauth

        {{-- ════════════════════════════════════════════════════════
             STEP 1 — DATA PELAPOR (GUEST ONLY)
         ════════════════════════════════════════════════════════ --}}
        @if ($step === 1)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
            <h2 class="text-lg font-bold text-gray-900 mb-5">Data Pelapor</h2>

            <div class="space-y-5">

                <div>
                    <label for="guestName" class="block text-sm font-medium text-gray-900 mb-2">
                        Nama Pelapor <span class="text-error">*</span>
                    </label>
                    <input
                        type="text" id="guestName"
                        wire:model="guestName"
                        placeholder="Masukkan nama Anda"
                        autofocus
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-10
                               text-gray-900 placeholder-gray-400 focus:bg-white
                               focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                               outline-none transition-all"
                    >
                    @error('guestName')
                        <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="guestPhone" class="block text-sm font-medium text-gray-900 mb-2">
                        Nomor Kontak (WhatsApp) <span class="text-error">*</span>
                    </label>
                    <input
                        type="tel" id="guestPhone"
                        wire:model="guestPhone"
                        placeholder="08123456789"
                        inputmode="numeric"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-10
                               text-gray-900 placeholder-gray-400 focus:bg-white
                               focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                               outline-none transition-all"
                    >
                    <p class="mt-1.5 text-xs text-gray-400">
                        Nomor WhatsApp untuk menerima detail laporan dan update status
                    </p>
                    @error('guestPhone')
                        <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="button"
                    wire:click="nextStep"
                    wire:loading.attr="disabled"
                    wire:target="nextStep"
                    class="w-full flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-700
                           disabled:opacity-60 text-white py-3 rounded-lg font-semibold text-sm
                           transition-all active:scale-[.98] shadow-sm"
                >
                    <span wire:loading.remove wire:target="nextStep">Lanjutkan</span>
                    <span wire:loading wire:target="nextStep" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Memproses...
                    </span>
                </button>

            </div>
        </div>
        @endif

        {{-- ════════════════════════════════════════════════════════
             STEP 2 — DETAIL LAPORAN (AUTH langsung, GUEST step 2)
         ════════════════════════════════════════════════════════ --}}
        @if ($step === 2)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
            @guest
                <h2 class="text-lg font-bold text-gray-900 mb-5">Detail Laporan</h2>
            @endguest

            <div class="space-y-6">

                {{-- Kategori --}}
                <div>
                    <label for="categoryId" class="block text-sm font-medium text-gray-900 mb-2">
                        Kategori Masalah <span class="text-error">*</span>
                    </label>
                    <select
                        id="categoryId" wire:model="categoryId"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-10
                               text-gray-900 focus:bg-white focus:ring-2 focus:ring-primary-500
                               focus:border-primary-500 outline-none transition-all"
                    >
                        <option value="" disabled selected>Pilih kategori masalah</option>
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('categoryId')
                        <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Judul --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-900 mb-2">
                        Judul Laporan <span class="text-error">*</span>
                    </label>
                    <input
                        type="text" id="title" wire:model="title"
                        placeholder="Contoh: Jalan berlubang di Jalan Sunset Road"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-10
                               text-gray-900 placeholder-gray-400 focus:bg-white
                               focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                               outline-none transition-all"
                    >
                    @error('title')
                        <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-900 mb-2">
                        Deskripsi Kejadian <span class="text-error">*</span>
                    </label>
                    <textarea
                        id="description" wire:model="description" rows="4"
                        placeholder="Jelaskan masalah secara detail (minimal 20 karakter)"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-10
                               text-gray-900 placeholder-gray-400 resize-none focus:bg-white
                               focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                               outline-none transition-all"
                    ></textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Lokasi + Leaflet ── --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Lokasi Kejadian <span class="text-error">*</span>
                    </label>

                    {{-- Input + tombol lokasi saat ini --}}
                    <div class="flex flex-col sm:flex-row gap-2.5 mb-2">
                        <input
                            type="text"
                            x-model="locationText"
                            @input="$wire.set('location', $event.target.value)"
                            placeholder="Alamat atau koordinat lokasi"
                            class="flex-1 px-4 py-3 rounded-lg border border-gray-200 bg-gray-10
                                   text-gray-900 placeholder-gray-400 focus:bg-white
                                   focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                   outline-none transition-all"
                        >
                        <button
                            type="button"
                            @click="useCurrentLocation()"
                            :disabled="locating"
                            class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg
                                   bg-secondary-500 hover:bg-secondary-700 disabled:opacity-60
                                   text-gray-900 text-sm font-semibold whitespace-nowrap
                                   transition-colors shrink-0"
                        >
                            <template x-if="!locating">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75
                                             7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5
                                             S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                                </svg>
                            </template>
                            <template x-if="locating">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                            </template>
                            <span x-text="locating ? 'Mencari...' : 'Lokasi Saat Ini'"></span>
                        </button>
                    </div>

                    {{-- Error geolocation --}}
                    <p x-show="geoError" x-text="geoError"
                       class="text-xs text-error mb-2" style="display:none;"></p>

                    @auth
                    <p class="text-xs text-gray-400 mb-3">
                        Sistem akan otomatis mengecek duplikasi laporan dalam radius 50 meter
                    </p>
                    @endauth

                    {{-- --- FIX 3: Wrapper diberi wire:ignore agar tidak hancur saat re-render Livewire --- --}}
                    <div wire:ignore class="rounded-xl overflow-hidden border border-gray-200 mb-2"
                         style="height: 280px; z-index: 0;">
                        <div id="report-map" class="w-full h-full"></div>
                    </div>
                    <p class="text-xs text-gray-400">
                        Klik pada peta untuk menandai lokasi, atau seret pin untuk fine-tune.
                    </p>

                    @error('location')
                        <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                    @enderror
                    @error('latitude')
                        <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kecamatan --}}
                <div>
                    <label for="districtId" class="block text-sm font-medium text-gray-900 mb-2">
                        Kecamatan <span class="text-error">*</span>
                    </label>
                    <select
                        id="districtId" wire:model="districtId"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-10
                               text-gray-900 focus:bg-white focus:ring-2 focus:ring-primary-500
                               focus:border-primary-500 outline-none transition-all"
                    >
                        <option value="" disabled selected>Pilih kecamatan</option>
                        @foreach ($districts as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                    @error('districtId')
                        <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto/Video Bukti --}}
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Foto/Video Bukti
                        @auth <span class="text-error">*</span>
                               <span class="text-gray-400 font-normal">(Maks. 5 file)</span>
                        @endauth
                        @guest <span class="text-gray-400 font-normal">(Opsional, maks. 5 file)</span>
                        @endguest
                    </label>

                    @auth
                    <div class="px-4 py-3 rounded-lg bg-blue-50 border border-blue-100 mb-3">
                        <p class="text-xs text-blue-700 leading-relaxed">
                            <span class="font-semibold">AI Analysis:</span>
                            Sistem akan otomatis menganalisis foto untuk mengidentifikasi kategori
                            dan memberikan tag spesifik masalah (contoh: jalan rusak, lubang aspal, sampah, dll).
                        </p>
                    </div>
                    @endauth

                    <label
                        for="evidenceFiles"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="dragging = false"
                        :class="dragging
                            ? 'border-primary-400 bg-primary-50'
                            : 'border-gray-200 bg-white hover:border-gray-300'"
                        class="flex flex-col items-center justify-center gap-2 py-10 px-6 rounded-xl
                               border-2 border-dashed cursor-pointer transition-colors"
                    >
                        <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 16V4m0 0L8 8m4-4l4 4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                        <p class="text-sm text-gray-600 text-center">Klik untuk upload atau drag and drop</p>
                        <p class="text-xs text-gray-400">PNG, JPG, MP4 (max. 10MB per file)</p>
                        <span class="mt-1 px-4 py-2 rounded-lg bg-gray-50 text-gray-700 text-sm font-medium">
                            Pilih File
                        </span>
                        <input
                            type="file" id="evidenceFiles"
                            wire:model="evidenceFiles"
                            accept="image/png,image/jpeg,image/jpg,video/mp4"
                            multiple class="hidden"
                        >
                    </label>

                    {{-- Preview --}}
                    @if (count($evidenceFiles) > 0)
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-3 mt-4">
                        @foreach ($evidenceFiles as $index => $f)
                        <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-100 border border-gray-200 group">
                            
                            {{-- Bedakan preview Image vs Video --}}
                            @if(str_starts_with($f->getMimeType(), 'video/'))
                                <video src="{{ $f->temporaryUrl() }}" class="w-full h-full object-cover" autoplay muted loop></video>
                            @else
                                <img src="{{ $f->temporaryUrl() }}" class="w-full h-full object-cover" alt="Preview">
                            @endif

                            {{-- Tombol Hapus (Muncul saat hover) --}}
                            <button 
                                type="button" 
                                wire:click="removeEvidence({{ $index }})"
                                class="absolute top-1.5 right-1.5 bg-red-500/90 hover:bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-all shadow-sm backdrop-blur-sm z-10 scale-90 group-hover:scale-100"
                                title="Hapus file ini"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Upload progress --}}
                    <div wire:loading wire:target="evidenceFiles" class="mt-2">
                        <p class="text-xs text-gray-500 flex items-center gap-1.5">
                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Mengupload file...
                        </p>
                    </div>

                    @error('evidenceFiles')
                        <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                    @enderror
                    @error('evidenceFiles.*')
                        <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Actions ── --}}
                <div class="flex gap-3 pt-3 border-t border-gray-100">
                    @guest
                    <button
                        type="button"
                        wire:click="prevStep"
                        class="px-5 py-3 rounded-lg border border-gray-200 text-gray-700 text-sm
                               font-semibold hover:bg-gray-10 transition-colors"
                    >
                        Kembali
                    </button>
                    @endguest

                    @auth
                    <a href="{{ route('home') }}"
                       class="px-5 py-3 rounded-lg border border-gray-200 text-gray-700 text-sm
                              font-semibold hover:bg-gray-10 transition-colors text-center">
                        Batal
                    </a>
                    @endauth

                    <button
                        type="button"
                        wire:click="submit"
                        wire:loading.attr="disabled"
                        wire:target="submit"
                        class="flex-1 flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-700
                               disabled:opacity-60 text-white py-3 rounded-lg font-semibold text-sm
                               transition-all active:scale-[.98] shadow-sm"
                    >
                        <span wire:loading.remove wire:target="submit">Kirim Laporan</span>
                        <span wire:loading wire:target="submit" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Mengirim...
                        </span>
                    </button>
                </div>

            </div>
        </div>

        {{-- Info proses selanjutnya --}}
        <div class="mt-6 px-5 py-4 rounded-xl bg-blue-50 border border-blue-100">
            <p class="text-sm text-blue-700 leading-relaxed">
                <span class="font-semibold">Proses selanjutnya:</span>
                Setelah laporan dikirim, sistem akan otomatis mengecek duplikasi dan memetakan ke OPD
                yang berwenang. Anda akan menerima notifikasi WhatsApp dengan nomor tiket.
            </p>
        </div>
        @endif

    </div>
</div>

</div>{{-- End outer Livewire root div --}}