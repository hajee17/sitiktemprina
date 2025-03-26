@extends('layouts.master')

@section('content')
<div style="position: relative; width: 100vw; height: 100vh; background: #FFFFFF; display: flex; justify-content: center; align-items: center;">
    
    <div style="width: 1200px; height: 760px; background: #ECEBE4; border: 4px solid #000000; border-radius: 40px; display: flex; overflow: hidden;">
        
        <!-- Bagian Kanan (Background Gambar) -->
        <div style="width: 50%; background: url('{{ asset('images/login-image.png') }}') no-repeat center center; background-size: cover;">
        </div>

        <!-- Bagian Kiri (Form Registrasi) -->
        <div style="width: 50%; background: #FFFFFF; padding: 50px; display: flex; flex-direction: column; justify-content: center;">
            
            <!-- Tombol Masuk -->
            <div style="position: absolute; top: 20px; right: 30px;">
                {{-- <a href="{{ route('login') }}" style="text-decoration: none; background: black; color: white; padding: 10px 20px; border-radius: 20px;">Masuk</a> --}}
                <a href="#" style="text-decoration: none; background: black; color: white; padding: 10px 20px; border-radius: 20px;">Masuk</a>
            </div>

            <!-- Judul -->
            <div style="text-align: center; margin-bottom: 20px;">
                <h2 style="margin: 0; font-size: 28px;">Selamat Datang!</h2>
                <p style="color: #5E5E5E;">Buat akun Anda dan mulai sekarang.</p>
            </div>

            <!-- Form Registrasi (Statis) -->
            {{-- <form method="POST" action="{{ route('register') }}" style="display: flex; flex-direction: column; gap: 15px;"> --}}
            <div style="display: flex; flex-direction: column; gap: 15px;">
                {{-- @csrf --}}

                <div>
                    <label for="name" style="font-weight: bold;">Nama Lengkap</label>
                    <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    {{-- @error('name') <span style="color: red;">{{ $message }}</span> @enderror --}}
                </div>

                <div>
                    <label for="email" style="font-weight: bold;">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    {{-- @error('email') <span style="color: red;">{{ $message }}</span> @enderror --}}
                </div>

                <div>
                    <label for="phone" style="font-weight: bold;">Nomor Telepon</label>
                    <input type="text" id="phone" name="phone" placeholder="Masukkan nomor telepon" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    {{-- @error('phone') <span style="color: red;">{{ $message }}</span> @enderror --}}
                </div>

                <div>
                    <label for="password" style="font-weight: bold;">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    {{-- @error('password') <span style="color: red;">{{ $message }}</span> @enderror --}}
                </div>

                <div>
                    <input type="checkbox" id="terms" name="terms">
                    <label for="terms">Saya menyetujui <a href="#">Syarat Penggunaan</a> dan <a href="#">Kebijakan Privasi</a></label>
                </div>

                {{-- <button type="submit" style="width: 100%; padding: 12px; background: black; color: white; border: none; border-radius: 5px; font-size: 16px;">Buat Akun</button> --}}
                <button style="width: 100%; padding: 12px; background: black; color: white; border: none; border-radius: 5px; font-size: 16px;">Buat Akun</button>

                <div style="text-align: center; margin: 10px 0; color: #5E5E5E;">atau daftar dengan</div>

                <button style="width: 100%; padding: 10px; background: white; border: 1px solid gray; display: flex; justify-content: center; align-items: center; gap: 10px; border-radius: 5px;">
                    <img src="{{ asset('images/google-logo.png') }}" alt="Google" style="height: 20px;">
                    Daftar dengan Google
                </button>
            </div>
            {{-- </form> --}}
        </div>
    </div>

</div>
@endsection
