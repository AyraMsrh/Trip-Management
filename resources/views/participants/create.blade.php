@extends('layouts.main')

@section('title', 'Daftar peserta')

@section('content')
    @php
        $registeredCount = $trip->participants()->count();

        $remainingSlots = max(
            0,
            $trip->capacity - $registeredCount
        );
    @endphp

    <div class="mb-7 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
        <div>
            <div class="mb-3 flex flex-wrap items-center gap-2 text-[11px] font-semibold text-[#948b98]">
                <a
                    href="{{ route('trips.show', $trip) }}"
                    class="font-mono text-[#7c2f95] hover:text-[#35134f]"
                >
                    {{ $trip->trip_code }}
                </a>

                <span class="text-[#c4bac7]">/</span>
                <span>Pendaftaran peserta</span>
            </div>

            <h1 class="text-[clamp(1.8rem,3vw,2.55rem)] font-semibold tracking-[-0.045em] text-[#35134f]">
                Daftar peserta
            </h1>

            <p class="mt-2 text-sm text-[#776c7b]">
                Daftarkan peserta untuk lawatan
                <strong class="text-[#4c1d68]">{{ $trip->title }}</strong>.
            </p>
        </div>

        <a
            href="{{ route('trips.show', $trip) }}"
            class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-[#ddd3e1] bg-white px-4 text-xs font-bold text-[#5a4c60] hover:border-[#bca5c5] hover:text-[#63247c]"
        >
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    <section class="mb-6 rounded-2xl border border-[#e9e2eb] bg-white p-5 shadow-[0_5px_20px_rgba(53,19,79,0.035)] sm:p-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <div class="text-[10px] font-bold uppercase tracking-[0.1em] text-[#a098a3]">
                    Kod lawatan
                </div>

                <div class="mt-1.5 font-mono text-xs font-semibold text-[#4c1d68]">
                    {{ $trip->trip_code }}
                </div>
            </div>

            <div>
                <div class="text-[10px] font-bold uppercase tracking-[0.1em] text-[#a098a3]">
                    Destinasi
                </div>

                <div class="mt-1.5 text-xs font-semibold text-[#403744]">
                    {{ $trip->destination }}
                </div>
            </div>

            <div>
                <div class="text-[10px] font-bold uppercase tracking-[0.1em] text-[#a098a3]">
                    Slot tersedia
                </div>

                <div class="mt-1.5 text-xs font-semibold text-[#403744]">
                    {{ $remainingSlots }} daripada {{ $trip->capacity }}
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-[#e9e2eb] bg-white p-5 shadow-[0_5px_20px_rgba(53,19,79,0.035)] sm:p-7">
        <form
            method="POST"
            action="{{ route('trips.participants.store', $trip) }}"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="mb-6">
                <div class="flex items-center gap-2.5">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-[#f3eafa] text-[#7c2f95]">
                        <i class="bi bi-person-vcard"></i>
                    </span>

                    <div>
                        <h2 class="text-[15px] font-bold text-[#4c1d68]">
                            Maklumat peserta
                        </h2>

                        <p class="mt-1 text-[11px] text-[#9b929e]">
                            Masukkan maklumat seperti yang tertera dalam pasport.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label
                        for="full_name"
                        class="mb-2 block text-xs font-bold text-[#4c1d68]"
                    >
                        Nama penuh
                    </label>

                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        value="{{ old('full_name') }}"
                        placeholder="Seperti di dalam pasport"
                        required
                        class="h-11 w-full rounded-xl border border-[#ded9e2] bg-white px-3 text-sm outline-none transition placeholder:text-[#aaa4ad] focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10 @error('full_name') border-[#b24c5a] @enderror"
                    >

                    @error('full_name')
                        <p class="mt-1.5 text-xs text-[#b24c5a]">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="passport_number"
                        class="mb-2 block text-xs font-bold text-[#4c1d68]"
                    >
                        Nombor pasport
                    </label>

                    <input
                        type="text"
                        id="passport_number"
                        name="passport_number"
                        value="{{ old('passport_number') }}"
                        placeholder="Contoh: A12345678"
                        required
                        class="h-11 w-full rounded-xl border border-[#ded9e2] bg-white px-3 text-sm uppercase outline-none transition placeholder:normal-case placeholder:text-[#aaa4ad] focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10 @error('passport_number') border-[#b24c5a] @enderror"
                    >

                    @error('passport_number')
                        <p class="mt-1.5 text-xs text-[#b24c5a]">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="email"
                        class="mb-2 block text-xs font-bold text-[#4c1d68]"
                    >
                        Alamat emel
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        required
                        class="h-11 w-full rounded-xl border border-[#ded9e2] bg-white px-3 text-sm outline-none transition placeholder:text-[#aaa4ad] focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10 @error('email') border-[#b24c5a] @enderror"
                    >

                    @error('email')
                        <p class="mt-1.5 text-xs text-[#b24c5a]">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="phone"
                        class="mb-2 block text-xs font-bold text-[#4c1d68]"
                    >
                        Nombor telefon
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="Contoh: 012-3456789"
                        required
                        class="h-11 w-full rounded-xl border border-[#ded9e2] bg-white px-3 text-sm outline-none transition placeholder:text-[#aaa4ad] focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10 @error('phone') border-[#b24c5a] @enderror"
                    >

                    @error('phone')
                        <p class="mt-1.5 text-xs text-[#b24c5a]">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="passport"
                        class="mb-2 block text-xs font-bold text-[#4c1d68]"
                    >
                        Salinan pasport
                    </label>

                    <input
                        type="file"
                        id="passport"
                        name="passport"
                        accept=".pdf,.jpg,.jpeg,.png"
                        class="block h-11 w-full rounded-xl border border-[#ded9e2] bg-white px-3 py-2 text-xs text-[#665c69] outline-none file:mr-3 file:rounded-lg file:border-0 file:bg-[#f3eafa] file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-[#7c2f95] focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10 @error('passport') border-[#b24c5a] @enderror"
                    >

                    <p class="mt-1.5 text-[11px] text-[#958d98]">
                        Format PDF, JPG, JPEG atau PNG. Maksimum 5MB.
                    </p>

                    @error('passport')
                        <p class="mt-1.5 text-xs text-[#b24c5a]">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="payment_status"
                        class="mb-2 block text-xs font-bold text-[#4c1d68]"
                    >
                        Status bayaran
                    </label>

                    <div class="relative">
                        <select
                            id="payment_status"
                            name="payment_status"
                            required
                            class="h-11 w-full appearance-none rounded-xl border border-[#ded9e2] bg-white px-3 pr-10 text-sm text-[#302a33] outline-none transition focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10 @error('payment_status') border-[#b24c5a] @enderror"
                        >
                            <option
                                value="pending"
                                @selected(old('payment_status', 'pending') === 'pending')
                            >
                                Belum bayar
                            </option>

                            <option
                                value="partial"
                                @selected(old('payment_status') === 'partial')
                            >
                                Sebahagian
                            </option>

                            <option
                                value="paid"
                                @selected(old('payment_status') === 'paid')
                            >
                                Sudah bayar
                            </option>
                        </select>

                        <i class="bi bi-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-[#7c2f95]"></i>
                    </div>

                    @error('payment_status')
                        <p class="mt-1.5 text-xs text-[#b24c5a]">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a
                    href="{{ route('trips.show', $trip) }}"
                    class="inline-flex h-11 items-center justify-center rounded-xl border border-[#ddd3e1] bg-white px-5 text-sm font-bold text-[#5a4c60] hover:border-[#bca5c5] hover:text-[#63247c]"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-[#078f83] px-5 text-sm font-bold text-white hover:bg-[#04796e]"
                >
                    <i class="bi bi-person-plus"></i>
                    Simpan peserta
                </button>
            </div>
        </form>
    </section>
@endsection