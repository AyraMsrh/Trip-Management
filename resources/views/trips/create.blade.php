@extends('layouts.main')

@section('title', 'Tambah lawatan')

@section('content')
    <div class="mb-7 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
        <div>
            <div class="mb-3 flex items-center gap-2 text-[11px] font-semibold text-[#948b98]">
                <a
                    href="{{ route('trips.index') }}"
                    class="text-[#7c2f95] hover:text-[#35134f]"
                >
                    Lawatan
                </a>

                <span class="text-[#c4bac7]">/</span>
                <span>Tambah lawatan</span>
            </div>

            <h1 class="text-[clamp(1.8rem,3vw,2.55rem)] font-semibold tracking-[-0.045em] text-[#35134f]">
                Tambah lawatan
            </h1>

            <p class="mt-2 text-sm text-[#776c7b]">
                Cipta program lawatan baharu.
            </p>
        </div>

        <a
            href="{{ route('trips.index') }}"
            class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-[#ddd3e1] bg-white px-4 text-xs font-bold text-[#5a4c60] hover:border-[#bca5c5] hover:text-[#63247c]"
        >
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    <section class="rounded-2xl border border-[#e9e2eb] bg-white p-5 shadow-[0_5px_20px_rgba(53,19,79,0.035)] sm:p-7">
        <form
            method="POST"
            action="{{ route('trips.store') }}"
        >
            @csrf

            <div class="mb-6">
                <h2 class="text-[15px] font-bold text-[#4c1d68]">
                    Maklumat lawatan
                </h2>

                <p class="mt-1 text-[11px] text-[#9b929e]">
                    Masukkan butiran asas program lawatan.
                </p>
            </div>

            @include('trips.partials.form')

            <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a
                    href="{{ route('trips.index') }}"
                    class="inline-flex h-11 items-center justify-center rounded-xl border border-[#ddd3e1] bg-white px-5 text-sm font-bold text-[#5a4c60] hover:border-[#bca5c5] hover:text-[#63247c]"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-[#078f83] px-5 text-sm font-bold text-white hover:bg-[#04796e]"
                >
                    <i class="bi bi-check-lg"></i>
                    Simpan lawatan
                </button>
            </div>
        </form>
    </section>
@endsection