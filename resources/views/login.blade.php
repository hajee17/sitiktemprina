@extends('layouts.master')

@section('content')
<div style="position: relative; width: 100vw; height: 100vh; background: #FFFFFF; display: flex; justify-content: center; align-items: center;">
    
    <div style="width: 1200px; height: 660px; background: #ECEBE4; border: 4px solid #000000; border-radius: 40px; display: flex; overflow: hidden;">
        
        <!-- Bagian Kiri (Background Gambar) -->
        <div style="width: 50%; background: url('{{ asset('images/login-image.png') }}') no-repeat center center; background-size: cover;">
        </div>

        <!-- Bagian Kanan (Form Login) -->
        <div style="width: 50%; background: #FFFFFF; padding: 50px; display: flex; flex-direction: column; justify-content: center;">
            
            <!-- Tombol Daftar -->
            <div style="text-align: right; margin-bottom: 50px;">
                {{-- <a href="{{ route('register') }}" style="text-decoration: none; background: black; color: white; padding: 10px 20px; border-radius: 20px;">Daftar</a> --}}
                <a href="#" style="text-decoration: none; background: black; color: white; padding: 10px 20px; border-radius: 20px;">Daftar</a>
            </div>
            <!-- Judul -->
            <div style="text-align: left; margin-bottom: 20px;">
                <h2 style="margin: 0; font-size: 28px;">Selamat Datang Kembali!</h2>
                <p style="color: #5E5E5E;">Masuk ke akun Anda untuk melanjutkan.</p>
            </div>
            


            <!-- Form Login (Statis) -->
            {{-- <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 15px;"> --}}
            <div style="display: flex; flex-direction: column; gap: 15px;">
                {{-- @csrf --}}

                <div>
                    <label for="email" style="font-weight: bold;">Email / Nomor Telepon</label>
                    <input type="text" id="email" name="email" placeholder="Masukkan email atau nomor telepon" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    {{-- @error('email') <span style="color: red;">{{ $message }}</span> @enderror --}}
                </div>

                <div>
                    <label for="password" style="font-weight: bold;">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    {{-- @error('password') <span style="color: red;">{{ $message }}</span> @enderror --}}
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat Saya</label>
                    </div>
                    {{-- <a href="{{ route('password.request') }}" style="text-decoration: none; color: black;">Lupa Password?</a> --}}
                    <a href="#" style="text-decoration: none; color: black;">Lupa Password?</a>
                </div>

                {{-- <button type="submit" style="width: 100%; padding: 12px; background: black; color: white; border: none; border-radius: 5px; font-size: 16px;">Masuk</button> --}}
                <button style="width: 100%; padding: 12px; background: black; color: white; border: none; border-radius: 5px; font-size: 16px;">Masuk</button>

                <div style="text-align: center; margin: 10px 0; color: #5E5E5E;">atau masuk dengan</div>

                <button style="width: 100%; padding: 10px; background: white; border: 1px solid gray; display: flex; justify-content: center; align-items: center; gap: 10px; border-radius: 5px;">
                    <img src="{{ asset('images/google-logo.png') }}" alt="Google" style="height: 20px;">
                    Masuk dengan Google
                </button>
            </div>
            {{-- </form> --}}
        </div>
    </div>

</div>
@endsection
