<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILABA - Klaim Reward</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-[#F8F9FA]">

    <div class="w-full bg-[#F8F9FA] pb-16">
     
        <div class="w-full bg-[#C94A4A] text-white px-6 py-12 md:px-12 mb-6">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-2">Klaim Reward</h1>
                    <p class="text-sm opacity-90">Tukarkan poin reward Anda dengan berbagai hadiah menarik</p>
                </div>
                <div class="bg-[#A01414] border border-white/10 rounded-xl p-4 min-w-[150px] text-center shadow-inner">
                    <span class="block text-[10px] uppercase tracking-wider opacity-75 mb-1">Poin Reward Anda</span>
                    <span class="text-3xl font-extrabold tracking-tight">150</span>
                    <span class="block text-xs opacity-75 mt-0.5">Poin</span>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 md:px-6">
            {{-- Banner Informasi Blue --}}
            <div class="w-full bg-[#EBF5FF] border border-[#D1E7FF] rounded-lg p-4 text-left mb-8">
                <p class="text-xs text-[#1E429F] leading-relaxed">
                    <span class="font-bold">Info:</span> Poin reward didapatkan setelah laporan Anda diselesaikan dan diverifikasi. Semakin banyak laporan yang Anda buat, semakin banyak poin yang Anda kumpulkan!
                </p>
            </div>

            <h2 class="text-xl font-bold text-[#111827] mb-6">Katalog Reward</h2>

            {{-- Grid Katalog --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                @php
                    $items = [
                        ['title' => 'Voucher Pulsa Rp 25.000', 'cat' => 'Pulsa & Data', 'desc' => 'Voucher pulsa untuk semua operator (Telkomsel, XL, Indosat, Tri)', 'point' => '50', 'stok' => '100', 'icon' => 'kado', 'disabled' => false],
                        ['title' => 'Voucher Belanja Rp 50.000', 'cat' => 'Voucher', 'desc' => 'Berlaku di Indomaret, Alfamart, Circle K', 'point' => '100', 'stok' => '50', 'icon' => 'belanja', 'disabled' => false],
                        ['title' => 'Voucher Kopi Rp 25.000', 'cat' => 'F&B', 'desc' => 'Nikmati kopi di kedai lokal Bali pilihan', 'point' => '50', 'stok' => '75', 'icon' => 'kopi', 'disabled' => false],
                        ['title' => 'Tiket Garuda Wisnu Kencana', 'cat' => 'Wisata', 'desc' => 'Tiket masuk gratis ke GWK Cultural Park', 'point' => '150', 'stok' => '20', 'icon' => 'voucher', 'disabled' => false],
                        ['title' => 'Merchandise SILABA', 'cat' => 'Merchandise', 'desc' => 'Kaos, topi, atau tote bag eksklusif SILABA', 'point' => '75', 'stok' => '50', 'icon' => 'kado', 'disabled' => false],
                        ['title' => 'Voucher Makan Rp 100.000', 'cat' => 'F&B', 'desc' => 'Berlaku di warung dan restoran lokal partner', 'point' => '200', 'stok' => '25', 'icon' => 'kopi', 'disabled' => false],
                        ['title' => 'Sertifikat Penghargaan', 'cat' => 'Penghargaan', 'desc' => 'Sertifikat Warga Peduli dari Pemkab Badung', 'point' => '125', 'stok' => '200', 'icon' => 'sertifikat', 'disabled' => false],
                        ['title' => 'Tiket Taman Budaya Bali', 'cat' => 'Wisata', 'desc' => 'Tiket masuk gratis untuk 2 orang', 'point' => '100', 'stok' => '15', 'icon' => 'voucher', 'disabled' => false],
                        ['title' => 'Voucher Parkir Gratis', 'cat' => 'Transportasi', 'desc' => 'Voucher parkir 10x gratis di area publik Badung', 'point' => '75', 'stok' => '40', 'icon' => 'voucher', 'disabled' => false],
                        
                        // ITEM REVISI DISABLED
                        ['title' => 'Diskon Pajak Kendaraan 10%', 'cat' => 'Layanan Publik', 'desc' => 'Potongan pajak kendaraan bermotor periode berikutnya', 'point' => '300', 'stok' => '50', 'icon' => 'sertifikat', 'disabled' => true, 'btn' => 'Butuh 150 Poin Lagi'],
                        ['title' => 'Voucher Spa Rp 150.000', 'cat' => 'Wellness', 'desc' => 'Relaksasi di spa dan massage center partner', 'point' => '250', 'stok' => 'Habis', 'icon' => 'kado', 'disabled' => true, 'btn' => 'Tidak Tersedia'],
                        ['title' => 'Paket Data 10GB', 'cat' => 'Pulsa & Data', 'desc' => 'Paket internet 10GB untuk semua provider', 'point' => '100', 'stok' => 'Habis', 'icon' => 'kado', 'disabled' => true, 'btn' => 'Tidak Tersedia'],
                    ];
                @endphp

                @foreach($items as $item)
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm flex flex-col {{ $item['disabled'] ? 'opacity-80' : '' }}">
                    <div class="bg-[#FDF3F3] flex flex-col items-center justify-center py-6 border-b border-gray-100 {{ $item['disabled'] ? 'grayscale bg-gray-50' : '' }}">
                        
                        @if($item['icon'] == 'kado')
                            <svg width="56" height="56" viewBox="0 0 74 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 32.9958C5 15.325 19.325 1 36.9958 1C54.6665 1 68.9915 15.325 68.9915 32.9958C68.9915 50.6665 54.6665 64.9915 36.9958 64.9915C19.325 64.9915 5 50.6665 5 32.9958Z" fill="white"/>
                                <path d="M47.6608 27.6631H26.3302V32.9957H47.6608V27.6631Z" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M36.9961 27.6631V44.9941" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M46.3283 32.9957V42.3278C46.3283 43.035 46.0474 43.7132 45.5473 44.2132H30.3304C29.6232 44.9942 28.445 44.2132 27.6641 42.3278V32.9957" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M30.997 27.6631C30.113 27.6631 29.2653 27.3119 28.6402 26.6869C28.0152 26.0619 27.6641 25.2141 27.6641 24.3302C27.6641 23.4462 28.0152 22.5985 28.6402 21.9735C29.2653 21.3484 30.113 20.9973 30.997 20.9973C32.283 20.9749 33.5433 21.5989 34.6135 22.7879C35.6836 23.977 36.5139 25.6759 36.9962 27.6631C37.4784 25.6759 38.3087 23.977 39.3789 22.7879C40.449 21.5989 41.7093 20.9749 42.9954 20.9973C43.8793 20.9973 44.7271 21.3484 45.3521 21.9735C45.9771 22.5985 46.3283 23.4462 46.3283 24.3302C46.3283 25.2141 45.9771 26.0619 45.3521 26.6869C44.7271 27.3119 43.8793 27.6631 42.9954 27.6631" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                        @elseif($item['icon'] == 'belanja')
                            <svg width="56" height="56" viewBox="0 0 74 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 32.9958C5 15.325 19.325 1 36.9958 1C54.6665 1 68.9915 15.325 68.9915 32.9958C68.9915 50.6665 54.6665 64.9915 36.9958 64.9915C19.325 64.9915 5 50.6665 5 32.9958Z" fill="white"/>
                                <path d="M28.9965 19.6641L24.9971 24.9967V43.6609C24.9971 44.3681 25.278 45.0463 25.778 45.5463C26.278 46.0463 26.9562 46.3272 27.6634 46.3272H46.3276C47.0348 46.3272 47.7129 46.0463 48.213 45.5463C48.713 45.0463 48.9939 44.3681 48.9939 43.6609V24.9967L44.9944 19.6641H28.9965Z" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M24.9971 24.9967H48.9939" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M42.3284 30.3293C42.3284 31.7436 41.7665 33.1 40.7665 34.1001C39.7664 35.1002 38.41 35.662 36.9957 35.662C35.5814 35.662 34.225 35.1002 33.225 34.1001C32.2249 33.1 31.6631 31.7436 31.6631 30.3293" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                        @elseif($item['icon'] == 'kopi')
                            <svg width="56" height="56" viewBox="0 0 74 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 32.9958C5 15.325 19.325 1 36.9958 1C54.6665 1 68.9915 15.325 68.9915 32.9958C68.9915 50.6665 54.6665 64.9915 36.9958 64.9915C19.325 64.9915 5 50.6665 5 32.9958Z" fill="white"/>
                                <path d="M34.3301 19.6641V22.3304" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M39.6621 19.6641V22.3304" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M42.3281 27.6631C42.6817 27.6631 43.0208 27.8035 43.2708 28.0536C43.5208 28.3036 43.6613 28.6427 43.6613 28.9962V39.6615C43.6613 41.0758 43.0995 42.4322 42.0994 43.4323C41.0993 44.4323 39.743 44.9941 38.3287 44.9941H30.3297C28.9154 44.9941 27.559 44.4323 26.559 43.4323C25.5589 42.4322 24.9971 41.0758 24.9971 39.6615V28.9962C24.9971 28.6427 25.1375 28.3036 25.3875 28.0536C25.6376 27.8035 25.9767 27.6631 26.3302 27.6631H44.9944C46.4087 27.6631 47.7651 28.2249 48.7652 29.225C49.7652 30.225 50.3271 31.5814 50.3271 32.9957C50.3271 34.41 49.7652 35.7664 48.7652 36.7665C47.7651 37.7665 46.4087 38.3284 44.9944 38.3284H43.6613" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M28.9971 19.6641V22.3304" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                        @elseif($item['icon'] == 'voucher')
                            <svg width="56" height="56" viewBox="0 0 74 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 32.9958C5 15.325 19.325 1 36.9958 1C54.6665 1 68.9915 15.325 68.9915 32.9958C68.9915 50.6665 54.6665 64.9915 36.9958 64.9915C19.325 64.9915 5 50.6665 5 32.9958Z" fill="white"/>
                                <path d="M23.6641 28.9962C24.7248 28.9962 25.7421 29.4176 26.4921 30.1676C27.2422 30.9177 27.6635 31.935 27.6635 32.9957C27.6635 34.0564 27.2422 35.0737 26.4921 35.8237C25.7421 36.5738 24.7248 36.9952 24.7248 36.9952V39.6615C24.7248 40.3686 25.0057 41.0468 25.5057 41.5468C26.0058 42.0469 26.684 42.3278 27.3912 42.3278H48.7217C49.4289 42.3278 50.1071 42.0469 50.6071 41.5468C51.1071 41.0468 51.388 40.3686 51.388 39.6615V36.9952C50.3273 36.9952 49.31 36.5738 48.56 35.8237C47.81 35.0737 47.3887 34.0564 47.3887 32.9957C47.3887 31.935 47.81 30.9177 48.56 30.1676C49.31 29.4176 50.3273 28.9962 51.388 28.9962V26.3299C51.388 25.6227 51.1071 24.9446 50.6071 24.4445C50.1071 23.9445 49.4289 23.6636 48.7217 23.6636H27.3912C26.684 23.6636 26.0058 23.9445 25.5057 24.4445C25.0057 24.9446 24.7248 25.6227 24.7248 26.3299V28.9962Z" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M39.39 23.6636V26.3299" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M39.39 39.6614V42.3277" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M39.39 31.6626V34.3289" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                        @elseif($item['icon'] == 'sertifikat')
                            <svg width="56" height="56" viewBox="0 0 74 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 32.9958C5 15.325 19.325 1 36.9958 1C54.6665 1 68.9915 15.325 68.9915 32.9958C68.9915 50.6665 54.6665 64.9915 36.9958 64.9915C19.325 64.9915 5 50.6665 5 32.9958Z" fill="white"/>
                                <path d="M41.6313 34.1821L43.651 45.5486C43.6736 45.6825 43.6548 45.82 43.5972 45.9429C43.5395 46.0658 43.4457 46.1682 43.3283 46.2363C43.2109 46.3044 43.0755 46.3351 42.9402 46.3241C42.8049 46.3132 42.6761 46.2613 42.5711 46.1752L37.7984 42.593C37.568 42.4209 37.2881 42.3279 37.0005 42.3279C36.7129 42.3279 36.433 42.4209 36.2026 42.593L31.4219 46.1739C31.317 46.2598 31.1884 46.3117 31.0533 46.3226C30.9182 46.3335 30.7829 46.303 30.6656 46.235C30.5482 46.1671 30.4544 46.065 30.3966 45.9424C30.3388 45.8197 30.3198 45.6824 30.3421 45.5486L32.3605 34.1821" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M36.996 35.662C41.4137 35.662 44.995 32.0807 44.995 27.663C44.995 23.2453 41.4137 19.6641 36.996 19.6641C32.5783 19.6641 28.9971 23.2453 28.9971 27.663C28.9971 32.0807 32.5783 35.662 36.996 35.662Z" stroke="#C01818" stroke-width="2.66632" stroke-linecap="round" stroke-linejoin="round"/>
                        @endif

                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-2">{{ $item['cat'] }}</span>
                    </div>

                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-gray-400 mb-1 {{ !$item['disabled'] ? 'text-[#111827]' : '' }}">{{ $item['title'] }}</h3>
                            <p class="text-xs text-gray-400 leading-relaxed mb-4">{{ $item['desc'] }}</p>
                        </div>
                        <div>
                            <div class="flex justify-between items-end mb-4">
                                <div>
                                    <span class="block text-xl font-extrabold text-gray-400 {{ !$item['disabled'] ? 'text-[#C94A4A]' : '' }}">{{ $item['point'] }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Poin</span>
                                </div>
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded {{ $item['stok'] == 'Habis' ? 'text-red-600 bg-red-50' : 'text-emerald-600 bg-emerald-50' }}">Stok: {{ $item['stok'] }}</span>
                            </div>
                            
                            @if($item['disabled'])
                                <button class="w-full py-2.5 bg-gray-100 text-gray-400 text-xs font-bold rounded-lg cursor-not-allowed" disabled>{{ $item['btn'] }}</button>
                            @else
                                <button class="w-full py-2.5 bg-[#C94A4A] hover:bg-[#B33E3E] text-white text-xs font-bold rounded-lg transition-colors">Klaim Sekarang</button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

            </div>

            <div class="flex justify-center mt-12">
                <button class="flex items-center gap-3 px-6 py-2 border border-gray-400 bg-[#FFFFFF] hover:bg-gray-50 text-[#111827] text-xs font-bold rounded-lg shadow-sm transition-colors">
                    <svg class="w-10 h-10 shrink-0" viewBox="0 0 74 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 32.9958C5 15.325 19.325 1 36.9958 1C54.6665 1 68.9915 15.325 68.9915 32.9958C68.9915 50.6665 54.6665 64.9915 36.9958 64.9915C19.325 64.9915 5 50.6665 5 32.9958Z" fill="none"/>
                        <path d="M41.6313 34.1821L43.651 45.5486C43.6736 45.6825 43.6548 45.82 43.5972 45.9429C43.5395 46.0658 43.4457 46.1682 43.3283 46.2363C43.2109 46.3044 43.0755 46.3351 42.9402 46.3241C42.8049 46.3132 42.6761 46.2613 42.5711 46.1752L37.7984 42.593C37.568 42.4209 37.2881 42.3279 37.0005 42.3279C36.7129 42.3279 36.433 42.4209 36.2026 42.593L31.4219 46.1739C31.317 46.2598 31.1884 46.3117 31.0533 46.3226C30.9182 46.3335 30.7829 46.303 30.6656 46.235C30.5482 46.1671 30.4544 46.065 30.3966 45.9424C30.3388 45.8197 30.3198 45.6824 30.3421 45.5486L32.3605 34.1821" stroke="#111827" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M36.996 35.662C41.4137 35.662 44.995 32.0807 44.995 27.663C44.995 23.2453 41.4137 19.6641 36.996 19.6641C32.5783 19.6641 28.9971 23.2453 28.9971 27.663C28.9971 32.0807 32.5783 35.662 36.996 35.662Z" stroke="#111827" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Lihat Riwayat Reward</span>
                </button>
            </div>
            
        </div>
    </div>

</body>
</html>