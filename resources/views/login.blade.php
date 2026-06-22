@extends('layouts.app')

@section('content')
        <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-8">
                <div class="flex justify-center items-center mb-4">
                    <a href="{{ url('/') }}" class="text-4xl font-bold tracking-tight text-[#C01818] flex items-center">
                        <span>S</span>
                        <svg class="w-5 h-10 mx-0.5 text-[#C01818]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L10 8h4l-2 -6zM11 9v10h2V9h-2zM9 19h6v2H9v-2z"/>
                        </svg>
                        <span>LABA</span>
                    </a>
                </div>
                <h2 class="text-3xl font-bold mb-2">Masuk ke SILABA</h2>
                <p class="text-[#6B7280]">Masuk untuk melacak dan mengelola laporan Anda</p>
            </div>

            <div class="bg-[#F9FAFB] p-8 rounded-xl shadow-sm border border-gray-100 w-full max-w-md">
                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="username" class="block text-sm font-medium mb-2">NIK atau Nomor HP</label>
                        <input type="text" id="username" name="username" placeholder="Masukkan NIK atau nomor HP" required autofocus
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#C01818] focus:border-[#C01818] outline-none transition placeholder-gray-400 bg-white text-[#111]">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Masukkan password" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#C01818] focus:border-[#C01818] outline-none transition placeholder-gray-400 bg-white text-[#111] pr-12">
                            <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#6B7280] hover:text-[#111]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                class="h-4 w-4 text-[#C01818] focus:ring-[#C01818] border-gray-300 rounded accent-[#C01818]">
                            <label for="remember" class="ml-2 block text-sm text-[#6B7280]">Ingat saya</label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-[#C01818] hover:underline">Lupa password?</a>
                        </div>
                    </div>

                    <button type="submit" class="w-full flex justify-center items-center gap-2 bg-[#C01818] hover:bg-[#a11414] text-white p-3 rounded-lg font-medium transition duration-150">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Masuk
                    </button>

                    <div class="relative flex items-center py-2">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="flex-shrink-0 mx-4 text-[#6B7280] text-sm bg-white px-2">atau</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>

                    <a href="{{ url('/') }}" class="w-full flex justify-center items-center gap-2 bg-[#F5F5F5] border border-gray-200 hover:bg-gray-100 text-[#111] p-3 rounded-lg font-medium transition duration-150">
                        <svg class="w-5 h-5 text-[#111]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Masuk sebagai Tamu
                    </a>
                </form>
            </div>

            <p class="mt-8 text-center text-sm text-[#6B7280]">
                Belum punya akun? <a href="#" class="font-semibold text-[#C01818] hover:underline">Daftar sekarang</a>
            </p>
        </div>
    </body>
@endsection