{{--
    resources/views/livewire/report/create-report-wizard.blade.php
    
    ARSITEKTUR ALPINE:
    - Tidak ada nested x-data
    - Map        → window.reportMap()   pada div#map-wrapper (wire:ignore)
    - Searchable → window.srSelect()    pada masing-masing div dropdown
    - Keduanya SEJAJAR, bukan bersarang
--}}
<div>
 
{{-- ── Leaflet CSS ── --}}
@if ($step === 2)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endif
 
<script>
/* ─────────────────────────────────────────────────────────────
   srSelect  — searchable select yang komunikasi ke Livewire
   Dipanggil: x-data="srSelect({ items, wireKey, initial })"
   TIDAK boleh berada di dalam x-data lain (nested = crash)
───────────────────────────────────────────────────────────── */
window.srSelect = window.srSelect || function ({ items, wireKey, initial }) {
    return {
        isOpen:        false,
        search:        '',
        selected:      null,
        selectedLabel: '',
        allItems:      items,
 
        get filtered() {
            if (!this.search) return this.allItems;
            const q = this.search.toLowerCase();
            return this.allItems.filter(i => i.name.toLowerCase().includes(q));
        },
 
        init() {
            if (initial) {
                const found = this.allItems.find(i => String(i.id) === String(initial));
                if (found) { this.selected = found.id; this.selectedLabel = found.name; }
            }
        },
 
        toggle() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.$nextTick(() => {
                    const inp = this.$el.querySelector('input.sr-search');
                    if (inp) inp.focus();
                });
            }
        },
 
        pick(item) {
            this.selected      = item.id;
            this.selectedLabel = item.name;
            this.search        = '';
            this.isOpen        = false;
            this.$wire.set(wireKey, item.id);
        },
 
        clear() {
            this.selected      = null;
            this.selectedLabel = '';
            this.$wire.set(wireKey, '');
        }
    };
};
 
/* ─────────────────────────────────────────────────────────────
   reportMap  — Leaflet map Alpine component
   Dipanggil: x-data="reportMap()"  pada div#map-wrapper
   wire:ignore wajib ada pada wrapper-nya
───────────────────────────────────────────────────────────── */
/* ─────────────────────────────────────────────────────────────
   reportMap  — Leaflet map Alpine component
───────────────────────────────────────────────────────────── */
window.reportMap = window.reportMap || function () {
    return {
        locationText: '',
        locating:     false,
        geoError:     null,
        _map:         null,
        _marker:      null,
        DEFAULT_LAT:  -8.5833,
        DEFAULT_LNG:  115.1667,

        init() {
            // FIX 1: Sinkronisasi nilai awal dari Livewire ke input Alpine saat halaman dimuat
            this.locationText = this.$wire.location || '';
            
            this.$nextTick(() => this.initMap());

            // Pantau perubahan step dari Livewire, agar map langsung dirender saat masuk Step 2
            if (this.$wire) {
                this.$watch('$wire.step', () => {
                    this.$nextTick(() => this.initMap());
                });
            }
        },

        initMap() {
            const el = document.getElementById('report-map');
            if (!el) return; // Belum masuk ke Step 2 (DOM belum ada)

            // FIX 1: Cegah re-inisialisasi jika Map sudah ada.
            if (el._leaflet_id) {
                // Recovery instance map dari DOM jika Alpine state kereset oleh Livewire
                if (!this._map && el.__x_map) {
                    this._map = el.__x_map;
                    this._marker = el.__x_marker;
                }
                // Refresh ukuran peta agar tidak glitch/blank
                if (this._map) this._map.invalidateSize();
                return;
            }

            const doInit = () => {
                // Cegah double eksekusi akibat event onload ganda
                if (el._leaflet_id) return;

                const lat = this.$wire.latitude  ? parseFloat(this.$wire.latitude)  : this.DEFAULT_LAT;
                const lng = this.$wire.longitude ? parseFloat(this.$wire.longitude) : this.DEFAULT_LNG;

                this._map = L.map(el).setView([lat, lng], 13);
                
                // Simpan instance map secara aman ke elemen DOM
                el.__x_map = this._map; 

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap', maxZoom: 19,
                }).addTo(this._map);

                if (this.$wire.latitude && this.$wire.longitude) {
                    this._place(lat, lng);
                }

                this._map.on('click', e => {
                    this._place(e.latlng.lat, e.latlng.lng);
                    this._sync(e.latlng.lat, e.latlng.lng);
                    this._geocode(e.latlng.lat, e.latlng.lng);
                });
            };

            // FIX 2: Cegah penambahan tag <script> berkali-kali
            if (typeof L === 'undefined') {
                if (!document.getElementById('leaflet-script-tag')) {
                    const s = document.createElement('script');
                    s.id = 'leaflet-script-tag';
                    s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                    s.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
                    s.crossOrigin = '';
                    s.onload = doInit;
                    document.head.appendChild(s);
                } else {
                    // Script sudah ditambahkan tapi masih proses loading, antrikan callback
                    document.getElementById('leaflet-script-tag').addEventListener('load', doInit);
                }
            } else {
                doInit();
            }
        },

        _place(lat, lng) {
            if (this._marker) { 
                this._marker.setLatLng([lat, lng]); 
                return; 
            }
            this._marker = L.marker([lat, lng], { draggable: true }).addTo(this._map);
            
            // Backup marker ke DOM
            const el = document.getElementById('report-map');
            if (el) el.__x_marker = this._marker;

            this._marker.on('dragend', e => {
                const p = e.target.getLatLng();
                this._sync(p.lat, p.lng);
                this._geocode(p.lat, p.lng);
            });
        },

        _sync(lat, lng) {
            this.$wire.set('latitude',  parseFloat(lat).toFixed(7));
            this.$wire.set('longitude', parseFloat(lng).toFixed(7));
        },

        async _geocode(lat, lng) {
            try {
                const r = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18`,
                    { headers: { 'Accept-Language': 'id,en' } }
                );
                const d = await r.json();
                if (d?.display_name) {
                    // FIX 2: Update variabel Alpine agar teks di input berubah secara visual
                    this.locationText = d.display_name; 
                    
                    this.$wire.set('location', d.display_name);
                    window.dispatchEvent(new CustomEvent('location-updated', { detail: d.display_name }));
                }
            } catch(_) {}
        },

        useCurrentLocation() {
            this.geoError = null;
            if (!navigator.geolocation) { this.geoError = 'Browser tidak mendukung geolokasi.'; return; }
            this.locating = true;
            navigator.geolocation.getCurrentPosition(
                pos => {
                    const { latitude: lat, longitude: lng } = pos.coords;
                    if (this._map) this._map.setView([lat, lng], 17);
                    this._place(lat, lng);
                    this._sync(lat, lng);
                    this._geocode(lat, lng);
                    this.locating = false;
                },
                err => {
                    this.locating = false;
                    this.geoError = {
                        1: 'Izin lokasi ditolak. Klik peta untuk memilih manual.',
                        2: 'Lokasi tidak tersedia. Klik peta untuk memilih manual.',
                        3: 'Timeout. Silakan coba lagi.',
                    }[err.code] ?? 'Gagal mendapat lokasi.';
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        },
    };
};
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

                {{-- ── KATEGORI (searchable, x-data SENDIRI bukan nested) ── --}}
            <div
                x-data="srSelect({
                    items: @js($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values()),
                    wireKey: 'categoryId',
                    initial: '{{ $categoryId }}'
                })"
                @click.outside="isOpen = false"
                class="relative"
            >
                <label class="block text-sm font-medium text-gray-900 mb-2">
                    Kategori Masalah <span class="text-error">*</span>
                </label>

                <button type="button" @click="toggle()"
                        class="w-full px-4 py-3 rounded-lg border text-left flex items-center justify-between gap-2
                               transition-all outline-none text-sm"
                        :class="isOpen
                            ? 'border-primary-500 ring-2 ring-primary-500 bg-white'
                            : 'border-gray-200 bg-gray-10 hover:border-gray-300'">
                    <span class="truncate"
                          :class="selectedLabel ? 'text-gray-900' : 'text-gray-400'"
                          x-text="selectedLabel || 'Pilih kategori masalah'"></span>
                    <div class="flex items-center gap-1 shrink-0">
                        <span x-show="selected" @click.stop="clear()"
                              class="text-gray-300 hover:text-gray-500 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                                <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                             :class="isOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 16 16">
                            <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </button>

                <div x-show="isOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="absolute z-30 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden"
                     style="display:none;">
                    <div class="p-2 border-b border-gray-100">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                                 fill="none" viewBox="0 0 16 16">
                                <circle cx="6.5" cy="6.5" r="4.5" stroke="currentColor" stroke-width="1.3"/>
                                <path d="M10 10l3 3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                            </svg>
                            <input type="text" x-model="search"
                                   class="sr-search w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-gray-200
                                          focus:border-primary-500 focus:ring-1 focus:ring-primary-500
                                          outline-none bg-gray-10 focus:bg-white transition-all"
                                   placeholder="Cari kategori..."
                                   @keydown.escape="isOpen = false">
                        </div>
                    </div>
                    <ul class="max-h-52 overflow-y-auto py-1">
                        <template x-for="item in filtered" :key="item.id">
                            <li @click="pick(item)"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm cursor-pointer transition-colors"
                                :class="selected === item.id ? 'bg-primary-50 text-primary-600 font-semibold' : 'text-gray-700 hover:bg-gray-10'">
                                <svg class="w-4 h-4 shrink-0"
                                     :class="selected === item.id ? 'text-primary-500 opacity-100' : 'opacity-0'"
                                     fill="none" viewBox="0 0 16 16">
                                    <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span x-text="item.name"></span>
                            </li>
                        </template>
                        <li x-show="filtered.length === 0" class="px-4 py-6 text-sm text-gray-400 text-center">
                            Tidak ada hasil untuk "<span x-text="search" class="font-medium"></span>"
                        </li>
                    </ul>
                </div>

                @error('categoryId') <p class="mt-1.5 text-xs text-error">{{ $message }}</p> @enderror
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
                        x-data="{ dragging: false }"
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
                        <span class="mt-1 px-4 py-2 rounded-lg bg-gray-10 text-gray-700 text-sm font-medium">
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