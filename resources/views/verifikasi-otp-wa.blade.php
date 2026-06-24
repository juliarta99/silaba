<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILABA - Verifikasi WhatsApp</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-[#F8F9FA]">

    <div class="flex flex-col items-center justify-center min-h-screen px-4 py-12">
        
        <div class="flex items-center justify-center w-16 h-16 bg-[#FCE8E6] rounded-full mb-4">
            <svg class="w-7 h-7 text-[#C94A4A]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48L4.5 21l3.925-1.238A9.144 9.144 0 0 0 12 20.25Z" />
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-[#111827] text-center tracking-tight mb-1">
            Verifikasi WhatsApp
        </h1>
        <p class="text-sm text-gray-500 text-center mb-1">
            Kode OTP telah dikirim ke WhatsApp Anda
        </p>
        <p class="text-sm font-bold text-[#111827] text-center mb-6 tracking-wide">
            08XX****XXXX
        </p>

        <div class="w-full max-w-[490px] bg-white border border-gray-200 rounded-lg p-8 shadow-sm text-center mb-5">
            <label class="block text-sm font-bold text-[#111827] mb-4">
                Masukkan Kode OTP (6 Digit)
            </label>
            
            <div class="flex justify-center gap-2 mb-3">
                @for ($i = 0; $i < 6; $i++)
                    <input 
                        type="text" 
                        maxlength="1" 
                        class="w-12 h-14 text-center text-xl font-semibold border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#C94A4A] focus:border-transparent bg-[#FDFDFD]"
                    />
                @endfor
            </div>

            <p class="text-xs text-gray-500 mb-5">
                Kode OTP berlaku selama: <span class="font-bold text-[#111827]">1:59</span>
            </p>

            <button 
                type="button" 
                class="w-full flex items-center justify-center gap-2 py-3 bg-[#E39393] text-white text-sm font-semibold rounded-md shadow-sm cursor-not-allowed mb-5 transition-colors"
                disabled
            >
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Verifikasi
            </button>

            <p class="text-xs text-gray-500">
                Tidak menerima kode OTP?
            </p>
            <button type="button" class="mt-1 text-xs font-semibold text-[#E39393] hover:underline cursor-not-allowed" disabled>
                Kirim ulang dalam 1:59
            </button>
        </div>

        <div class="w-full max-w-[490px] bg-[#EBF5FF] border border-[#D1E7FF] rounded-md p-4 text-left mb-6">
            <p class="text-xs text-[#1E429F] leading-relaxed">
                <span class="font-bold">Catatan:</span> Pastikan nomor WhatsApp Anda aktif dan terhubung dengan internet. Periksa chat WhatsApp dari SILABA untuk mendapatkan kode OTP.
            </p>
        </div>

        <a href="#" class="text-xs font-medium text-gray-500 hover:text-gray-700 hover:underline transition-colors">
            Kembali ke pendaftaran
        </a>

    </div>

</body>
</html>