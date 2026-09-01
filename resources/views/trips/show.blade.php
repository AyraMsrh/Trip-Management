@extends('layouts.main')

@section('title', $trip->title)

@section('content')
    @php
        $statusLabels = [
            'draft' => 'Draf',
            'open' => 'Dibuka',
            'closed' => 'Ditutup',
            'completed' => 'Selesai',
        ];

        $statusClasses = [
            'draft' => 'border-[#d8c2e2] bg-[#f3eafa] text-[#7c2f95]',
            'open' => 'border-[#9eddd4] bg-[#e8f7f3] text-[#086f69]',
            'closed' => 'border-[#ddd9df] bg-[#f0eef1] text-[#68646b]',
            'completed' => 'border-[#ddd9df] bg-[#f0eef1] text-[#68646b]',
        ];

        $participants = $trip->participants;
        $participantCount = $participants->count();
        $remainingSlots = max(
            0,
            $trip->capacity - $participantCount
        );

        $paidCount = $participants->filter(function ($participant) {
            return $participant->pivot->payment_status === 'paid';
        })->count();

        $partialCount = $participants->filter(function ($participant) {
            return $participant->pivot->payment_status === 'partial';
        })->count();

        $pendingCount = $participants->filter(function ($participant) {
            return $participant->pivot->payment_status === 'pending';
        })->count();

        // Single source of truth for activity ordering: newest first.
        $activityLogs = $trip->activityLogs
            ->sortByDesc('created_at')
            ->values();

        $activityInitialLimit = 6;
        $activityInitialLogs = $activityLogs->take($activityInitialLimit);
        $activityRemainingLogs = $activityLogs->slice($activityInitialLimit)->values();

        $registrationPercentage = $trip->capacity > 0
            ? min(
                100,
                round(($participantCount / $trip->capacity) * 100)
            )
            : 0;

        $paymentPercentage = $participantCount > 0
            ? round(($paidCount / $participantCount) * 100)
            : 0;
    @endphp

    <div class="mb-6 flex flex-col justify-between gap-5 xl:flex-row xl:items-end">
        <div>
            <div class="mb-3 flex items-center gap-2 text-[11px] font-semibold text-[#948b98]">
                <a href="{{ route('trips.index') }}" class="text-[#7c2f95] hover:text-[#35134f]">
                    Lawatan
                </a>

                <span class="text-[#c4bac7]">/</span>

                <span class="font-mono text-[10px] tracking-[0.04em] text-[#7c2f95]">
                    {{ $trip->trip_code }}
                </span>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-[clamp(1.8rem,3vw,2.55rem)] font-semibold tracking-[-0.045em] text-[#35134f]">
                    {{ $trip->title }}
                </h1>

                <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.08em] {{ $statusClasses[$trip->status] ?? 'border-[#ddd9df] bg-[#f0eef1] text-[#68646b]' }}">
                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                    {{ $statusLabels[$trip->status] ?? $trip->status }}
                </span>
            </div>

            <div class="mt-2.5 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-[#776c7b]">
                <span class="inline-flex items-center gap-1.5">
                    <i class="bi bi-geo-alt text-[#0a978b]"></i>
                    {{ $trip->destination }}
                </span>

                <span class="hidden h-1 w-1 rounded-full bg-[#c8becb] sm:block"></span>

                <span class="inline-flex items-center gap-1.5">
                    <i class="bi bi-calendar3 text-[#0a978b]"></i>
                    {{ $trip->start_date->format('d M Y') }}
                    hingga
                    {{ $trip->end_date->format('d M Y') }}
                </span>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('trips.edit', $trip) }}" class="inline-flex h-10 items-center gap-2 rounded-xl border border-[#ddd3e1] bg-white px-4 text-xs font-bold text-[#5a4c60] shadow-[0_3px_12px_rgba(53,19,79,0.035)] transition hover:-translate-y-0.5 hover:border-[#bca5c5] hover:text-[#63247c]">
                <i class="bi bi-pencil"></i>
                Kemaskini
            </a>

        </div>
    </div>

    <div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="rounded-2xl border border-[#e9e2eb] bg-white p-4 shadow-[0_5px_20px_rgba(53,19,79,0.035)] sm:p-5">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[11px] font-semibold leading-4 text-[#8b818f]">
                    Peserta berdaftar
                </span>

                <span class="grid h-9 w-9 place-items-center rounded-xl bg-[#f3eafa] text-[#7c2f95]">
                    <i class="bi bi-people-fill"></i>
                </span>
            </div>

            <div class="mt-3 flex items-end gap-2">
                <span class="text-[26px] font-bold leading-none tracking-[-0.05em] text-[#35134f]">
                    {{ $participantCount }}
                </span>

                <span class="pb-0.5 text-[10px] text-[#a096a3]">
                    daripada {{ $trip->capacity }} slot
                </span>
            </div>
        </div>

        <div class="rounded-2xl border border-[#e9e2eb] bg-white p-4 shadow-[0_5px_20px_rgba(53,19,79,0.035)] sm:p-5">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[11px] font-semibold leading-4 text-[#8b818f]">
                    Slot tersedia
                </span>

                <span class="grid h-9 w-9 place-items-center rounded-xl bg-[#e4f7f3] text-[#087c73]">
                    <i class="bi bi-person-check-fill"></i>
                </span>
            </div>

            <div class="mt-3 flex items-end gap-2">
                <span class="text-[26px] font-bold leading-none tracking-[-0.05em] text-[#35134f]">
                    {{ $remainingSlots }}
                </span>

                <span class="pb-0.5 text-[10px] text-[#a096a3]">
                    boleh didaftarkan
                </span>
            </div>
        </div>

        <div class="rounded-2xl border border-[#e9e2eb] bg-white p-4 shadow-[0_5px_20px_rgba(53,19,79,0.035)] sm:p-5">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[11px] font-semibold leading-4 text-[#8b818f]">
                    Kapasiti
                </span>

                <span class="grid h-9 w-9 place-items-center rounded-xl bg-[#f3eafa] text-[#7c2f95]">
                    <i class="bi bi-people"></i>
                </span>
            </div>

            <div class="mt-3 flex items-end gap-2">
                <span class="text-[26px] font-bold leading-none tracking-[-0.05em] text-[#35134f]">
                    {{ $trip->capacity }}
                </span>

                <span class="pb-0.5 text-[10px] text-[#a096a3]">
                    jumlah peserta
                </span>
            </div>
        </div>

        <div class="rounded-2xl border border-[#e9e2eb] bg-white p-4 shadow-[0_5px_20px_rgba(53,19,79,0.035)] sm:p-5">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[11px] font-semibold leading-4 text-[#8b818f]">
                    Bayaran penuh
                </span>

                <span class="grid h-9 w-9 place-items-center rounded-xl bg-[#fff4df] text-[#b5772c]">
                    <i class="bi bi-credit-card"></i>
                </span>
            </div>

            <div class="mt-3 flex items-end gap-2">
                <span class="text-[26px] font-bold leading-none tracking-[-0.05em] text-[#35134f]">
                    {{ $paidCount }}
                </span>

                <span class="pb-0.5 text-[10px] text-[#a096a3]">
                    peserta selesai
                </span>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="grid items-stretch gap-6 lg:grid-cols-3">
            <section class="rounded-2xl border border-[#e9e2eb] bg-white p-4 shadow-[0_5px_20px_rgba(53,19,79,0.035)] sm:p-5 lg:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-[15px] font-bold tracking-[-0.02em] text-[#4c1d68]">
                            Maklumat lawatan
                        </h2>

                        <p class="mt-1 text-[11px] text-[#9b929e]">
                            Ringkasan program dan pemilik rekod
                        </p>
                    </div>

                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-[#f3eafa] text-[#7c2f95]">
                        <i class="bi bi-info-circle"></i>
                    </span>
                </div>

                <div class="grid gap-x-5 gap-y-4 sm:grid-cols-3">
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-[0.1em] text-[#a098a3]">
                            Kod lawatan
                        </div>

                        <div class="mt-1 font-mono text-xs font-semibold text-[#4c1d68]">
                            {{ $trip->trip_code }}
                        </div>
                    </div>

                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-[0.1em] text-[#a098a3]">
                            Dicipta oleh
                        </div>

                        <div class="mt-1 text-xs font-semibold text-[#403744]">
                            {{ $trip->creator->name }}
                        </div>
                    </div>

                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-[0.1em] text-[#a098a3]">
                            Tarikh dicipta
                        </div>

                        <div class="mt-1 text-xs font-semibold text-[#403744]">
                            {{ $trip->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <div class="text-[10px] font-bold uppercase tracking-[0.1em] text-[#a098a3]">
                            Penerangan
                        </div>

                        <p class="mt-1 text-xs leading-6 text-[#665c69]">
                            {{ $trip->description ?: 'Tiada penerangan diberikan.' }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="flex flex-col justify-between rounded-2xl border border-[#e9e2eb] bg-[#35134f] p-5 text-white shadow-[0_10px_30px_rgba(53,19,79,0.12)] lg:col-span-1">
                <div>
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-[0.13em] text-white/50">
                                Kemajuan pendaftaran
                            </div>

                            <div class="mt-2 text-[25px] font-bold tracking-[-0.05em]">
                                {{ $registrationPercentage }}%
                            </div>
                        </div>

                        <span class="grid h-9 w-9 place-items-center rounded-xl bg-white/10 text-[#7de0d4]">
                            <i class="bi bi-people"></i>
                        </span>
                    </div>

                    <div class="mt-4 h-2 overflow-hidden rounded-full bg-white/10">
                        <div class="h-full rounded-full bg-[#0bb6a3]" style="width: {{ $registrationPercentage }}%;"></div>
                    </div>

                    <div class="mt-3 flex justify-between text-[10px] text-white/55">
                        <span>{{ $participantCount }} slot digunakan</span>
                        <span>{{ $remainingSlots }} slot tersedia</span>
                    </div>
                </div>

                @if($remainingSlots > 0 && $trip->status === 'open')
                    <a href="{{ route('trips.participants.create', $trip) }}" class="mt-5 inline-flex h-9 w-full items-center justify-center gap-2 rounded-lg border border-white/15 bg-white/[0.08] text-[11px] font-bold text-white transition hover:bg-white/[0.14]">
                        <i class="bi bi-person-plus"></i>
                        Daftar peserta
                    </a>
                @endif
            </section>
        </div>

        <section id="participants" class="overflow-hidden rounded-2xl border border-[#e9e2eb] bg-white shadow-[0_5px_20px_rgba(53,19,79,0.035)]">
            <div class="flex flex-col justify-between gap-4 border-b border-[#eee8f0] p-5 sm:flex-row sm:items-center sm:p-6">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="text-[15px] font-bold tracking-[-0.02em] text-[#4c1d68]">
                            Peserta berdaftar
                        </h2>

                        <span class="rounded-full bg-[#f3eafa] px-2 py-0.5 text-[10px] font-bold text-[#7c2f95]">
                            {{ $participantCount }}
                        </span>
                    </div>

                    <p class="mt-1 text-[11px] text-[#9b929e]">
                        {{ $participantCount }} daripada {{ $trip->capacity }} slot digunakan
                        <span class="mx-1 text-[#d5ccd7]">·</span>
                        {{ $registrationPercentage }}% kapasiti
                    </p>
                </div>

                @if($remainingSlots > 0 && $trip->status === 'open')
                    <a href="{{ route('trips.participants.create', $trip) }}" class="inline-flex h-9 items-center justify-center gap-2 self-start rounded-lg bg-[#078f83] px-3.5 text-[11px] font-bold text-white hover:bg-[#04796e] sm:self-auto">
                        <i class="bi bi-person-plus"></i>
                        Tambah peserta
                    </a>
                @elseif($trip->status !== 'open')
                    <span class="inline-flex rounded-full bg-[#f0eef1] px-3 py-1.5 text-[11px] font-bold text-[#68646b]">
                        Pendaftaran ditutup
                    </span>
                @else
                    <span class="inline-flex rounded-full bg-[#fff0f1] px-3 py-1.5 text-[11px] font-bold text-[#a33f4b]">
                        Lawatan telah penuh
                    </span>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] border-collapse text-left">
                    <thead>
                        <tr class="bg-[#fcfbfd] text-[10px] font-bold uppercase tracking-[0.1em] text-[#928995]">
                            <th class="px-5 py-3.5 sm:px-6">Nama peserta</th>
                            <th class="px-4 py-3.5">Nombor pasport</th>
                            <th class="px-4 py-3.5">Emel</th>
                            <th class="px-4 py-3.5">Pasport</th>
                            <th class="px-4 py-3.5">Status bayaran</th>
                            <th class="px-4 py-3.5">Tarikh daftar</th>
                            <th class="px-5 py-3.5 text-right sm:px-6">Tindakan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#f0ebf2]">
                        @forelse($trip->participants as $participant)
                            @php
                                $paymentStatus = $participant->pivot->payment_status;

                                $initials = collect(explode(' ', trim($participant->full_name)))
                                    ->filter()
                                    ->take(2)
                                    ->map(fn ($name) => strtoupper(substr($name, 0, 1)))
                                    ->implode('');
                            @endphp

                            <tr class="transition odd:bg-white even:bg-[#fdfbfe] hover:bg-[#f9f3fc]">
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center gap-2.5">
                                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-[#f3eafa] text-[10px] font-extrabold text-[#7c2f95]">
                                            {{ $initials }}
                                        </span>

                                        <span class="text-xs font-bold text-[#39313d]">
                                            {{ $participant->full_name }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-4 py-4 font-mono text-[11px] text-[#6e6471]">
                                    {{ $participant->passport_number }}
                                </td>

                                <td class="px-4 py-4 text-[11px] text-[#6e6471]">
                                    {{ $participant->email }}
                                </td>

                                <td class="px-4 py-4">
                                    @if($participant->passport_path)
                                        <a href="{{ asset('storage/' . $participant->passport_path) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-[#7c2f95] hover:text-[#35134f]">
                                            <i class="bi bi-file-earmark-text"></i>
                                            Lihat fail
                                            <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                                        </a>
                                    @else
                                        <span class="text-[11px] text-[#aaa2ad]">
                                            Tiada fail
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-4">
                                    <form method="POST" action="{{ route('trips.participants.payment', [$trip, $participant]) }}">
                                        @csrf
                                        @method('PATCH')

                                        <div class="relative w-[142px] min-w-[142px]">
                                            <select name="payment_status" aria-label="Status bayaran {{ $participant->full_name }}" onchange="this.form.submit()" class="h-9 w-full appearance-none rounded-lg border border-[#d9d1dd] bg-[#fdfcfe] px-3 pr-10 text-[12px] font-semibold text-[#3e3544] outline-none transition hover:border-[#a88eb2] focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10">
                                                <option value="pending" @selected($paymentStatus === 'pending')>
                                                    Belum bayar
                                                </option>

                                                <option value="partial" @selected($paymentStatus === 'partial')>
                                                    Sebahagian
                                                </option>

                                                <option value="paid" @selected($paymentStatus === 'paid')>
                                                    Sudah bayar
                                                </option>
                                            </select>

                                            <i class="bi bi-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-[#7c2f95]"></i>
                                        </div>
                                    </form>
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 text-[11px] text-[#6e6471]">
                                    {{ \Carbon\Carbon::parse($participant->pivot->registered_at)->format('d M Y') }}
                                </td>

                                <td class="px-5 py-4 text-right sm:px-6">
                                    <form method="POST" action="{{ route('trips.participants.destroy', [$trip, $participant]) }}" onsubmit="return confirm('Adakah anda pasti mahu membatalkan pendaftaran ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" title="Batalkan pendaftaran" class="inline-grid h-8 w-8 place-items-center rounded-lg border border-[#f0cbd0] text-[#b24c5a] transition hover:bg-[#fff3f3]">
                                            <i class="bi bi-person-dash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center sm:px-6">
                                    <i class="bi bi-people text-4xl text-[#c4bac7]"></i>

                                    <p class="mt-4 text-sm font-bold text-[#4c1d68]">
                                        Tiada peserta berdaftar
                                    </p>

                                    <p class="mt-1 text-xs text-[#958d98]">
                                        Daftarkan peserta pertama untuk lawatan ini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between border-t border-[#eee8f0] px-5 py-3 text-[10px] text-[#a098a3] sm:px-6">
                <span>Seret untuk melihat maklumat lain</span>

                <span class="flex items-center gap-1.5 font-semibold text-[#7c2f95]">
                    <i class="bi bi-shield-check"></i>
                    Data terurus
                </span>
            </div>
        </section>

        <div class="grid items-start gap-6 lg:grid-cols-2">
            <section class="rounded-2xl border border-[#e9e2eb] bg-white p-5 shadow-[0_5px_20px_rgba(53,19,79,0.035)] sm:p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-[#4c1d68]">
                        Ringkasan bayaran
                    </h2>

                    <i class="bi bi-credit-card text-[#0a978b]"></i>
                </div>

                <div class="space-y-3.5">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-[#817783]">Sudah bayar</span>
                        <span class="font-bold text-[#087c73]">
                            {{ $paidCount }} peserta
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-[#817783]">Sebahagian</span>
                        <span class="font-bold text-[#b5772c]">
                            {{ $partialCount }} peserta
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-[#817783]">Belum bayar</span>
                        <span class="font-bold text-[#ad5663]">
                            {{ $pendingCount }} peserta
                        </span>
                    </div>
                </div>

                <div class="mt-5 border-t border-[#f0ebf2] pt-4">
                    <div class="mb-2 flex justify-between text-[10px] font-bold uppercase tracking-[0.08em] text-[#a098a3]">
                        <span>Status keseluruhan</span>
                        <span class="text-[#7c2f95]">
                            {{ $paymentPercentage }}%
                        </span>
                    </div>

                    <div class="h-1.5 overflow-hidden rounded-full bg-[#f0ebf2]">
                        <div class="h-full rounded-full bg-[#7c2f95]" style="width: {{ $paymentPercentage }}%;"></div>
                    </div>
                </div>
            </section>

            @if($activityLogs->count())
                <section class="rounded-2xl border border-[#e9e2eb] bg-white p-5 shadow-[0_5px_20px_rgba(53,19,79,0.035)] sm:p-6">
                    <div class="mb-3 flex items-center justify-between">
                        <div>
                            <h2 class="text-[15px] font-bold tracking-[-0.02em] text-[#4c1d68]">
                                Sejarah aktiviti
                            </h2>

                            <p class="mt-1 text-[11px] text-[#9b929e]">
                                Perubahan terbaru untuk lawatan ini
                            </p>
                        </div>

                        <i class="bi bi-clock-history text-[#7c2f95]"></i>
                    </div>

                    <div class="divide-y divide-[#f0ebf2]">
                        @foreach($activityInitialLogs as $log)
                            <div class="flex gap-3.5 py-4 first:pt-2">
                                <span class="mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-[#f3eafa] text-[#7c2f95]">
                                    <i class="bi bi-clock-history"></i>
                                </span>

                                <div>
                                    <div class="text-xs font-semibold leading-5 text-[#4b414f]">
                                        {{ $log->description }}
                                    </div>

                                    <div class="mt-1 text-[10px] text-[#9b929e]">
                                        Oleh {{ $log->user->name }}
                                        <span class="mx-1 text-[#d6cdd8]">·</span>
                                        {{ $log->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($activityRemainingLogs->isNotEmpty())
                        <div id="activity-log-extra-{{ $trip->id }}" class="hidden divide-y divide-[#f0ebf2]">
                            @foreach($activityRemainingLogs as $log)
                                <div class="flex gap-3.5 py-4">
                                    <span class="mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-[#f3eafa] text-[#7c2f95]">
                                        <i class="bi bi-clock-history"></i>
                                    </span>

                                    <div>
                                        <div class="text-xs font-semibold leading-5 text-[#4b414f]">
                                            {{ $log->description }}
                                        </div>

                                        <div class="mt-1 text-[10px] text-[#9b929e]">
                                            Oleh {{ $log->user->name }}
                                            <span class="mx-1 text-[#d6cdd8]">·</span>
                                            {{ $log->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" onclick="document.getElementById('activity-log-extra-{{ $trip->id }}').classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180'); this.querySelector('span').textContent = this.querySelector('span').textContent.trim() === 'Papar semua' ? 'Sembunyikan' : 'Papar semua';" class="mt-2 flex w-full items-center justify-center gap-1.5 rounded-lg border border-[#eee5f2] py-2 text-[11px] font-bold text-[#7c2f95] transition hover:bg-[#faf5fc]">
                            <span>Papar semua</span>
                            <i class="bi bi-chevron-down text-[10px] transition-transform"></i>
                        </button>
                    @endif
                </section>
            @endif
        </div>
    </div>
@endsection