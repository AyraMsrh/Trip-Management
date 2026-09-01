@extends('layouts.guest')

@section('content')
    <div class="auth-wrapper">
        <section class="auth-intro">
            <div class="auth-intro-logo">
                <img
                    src="{{ asset('images/insaaf-logo.png') }}"
                    alt="Logo In Saaff Travel and Tours"
                >
            </div>

            <div class="auth-intro-content">
                <h1>
                    Sistem Pengurusan Lawatan
                </h1>

                <p>
                    Urus lawatan, peserta, dokumen pasport dan status bayaran
                    dengan lebih tersusun.
                </p>

                
            </div>
        </section>

        <section class="auth-form-panel">
            <div class="auth-form-container">
                <h2>
                    Selamat datang kembali
                </h2>

                <p class="auth-subtitle">
                    Log masuk untuk mengakses sistem pengurusan lawatan.
                </p>

                @if(session('status'))
                    <div class="auth-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="auth-error">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('login') }}"
                >
                    @csrf

                    <div class="auth-field">
                        <label
                            for="email"
                            class="auth-form-label"
                        >
                            Alamat emel
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="auth-form-control"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            required
                            autofocus
                        >
                    </div>

                    <div class="auth-field">
                        <label
                            for="password"
                            class="auth-form-label"
                        >
                            Kata laluan
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="auth-form-control"
                            placeholder="Masukkan kata laluan"
                            required
                        >
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="d-flex align-items-center gap-2 small">
                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                            >

                            Ingat saya
                        </label>

                        @if(Route::has('password.request'))
                            <a
                                href="{{ route('password.request') }}"
                                class="small text-decoration-none"
                                style="color: #069b8c;"
                            >
                                Lupa kata laluan?
                            </a>
                        @endif
                    </div>

                    <button
                        type="submit"
                        class="auth-submit"
                    >
                        Log masuk
                        <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>

                @if(Route::has('register'))
                    <div class="auth-footer">
                        Belum mempunyai akaun?

                        <a href="{{ route('register') }}">
                            Daftar akaun
                        </a>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection