@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    @php
        $statusLabels = [
            'draft' => 'Draf',
            'open' => 'Dibuka',
            'closed' => 'Ditutup',
            'completed' => 'Selesai',
        ];

        $statusClasses = [
            'draft' => 'bg-[#f3eafa] text-[#7c2f95]',
            'open' => 'bg-[#e4f7f3] text-[#087c73]',
            'closed' => 'bg-[#f0eef1] text-[#68646b]',
            'completed' => 'bg-[#f0eef1] text-[#68646b]',
        ];

        $dashboardStats = [
            [
                'label' => 'Jumlah lawatan',
                'value' => $totalTrips,
                'caption' => 'semua program',
                'icon' => 'bi-airplane',
                'tone' => 'purple',
            ],
            [
                'label' => 'Lawatan akan datang',
                'value' => $upcomingTrips,
                'caption' => 'program aktif',
                'icon' => 'bi-calendar-check',
                'tone' => 'teal',
            ],
            [
                'label' => 'Jumlah peserta',
                'value' => $totalParticipants,
                'caption' => 'semua lawatan',
                'icon' => 'bi-people-fill',
                'tone' => 'purple',
            ],
            [
                'label' => 'Bayaran belum selesai',
                'value' => $pendingPayments,
                'caption' => 'perlu tindakan',
                'icon' => 'bi-credit-card',
                'tone' => 'gold',
            ],
        ];
    @endphp

    <div class="mb-7 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
        <div>
            <h1 class="text-[clamp(1.8rem,3vw,2.55rem)] font-extrabold tracking-[-0.045em] text-[#35134f]">
                Dashboard
            </h1>

            <p class="mt-2 text-sm text-[#776c7b]">
                Selamat datang kembali, {{ auth()->user()->name }}.
            </p>
        </div>

        <a
            href="{{ route('trips.create') }}"
            class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-[#078f83] px-4 text-sm font-bold text-white shadow-[0_7px_16px_rgba(6,155,140,0.16)] transition hover:-translate-y-0.5 hover:bg-[#04796e]"
        >
            <i class="bi bi-plus-lg"></i>
            Tambah lawatan
        </a>
    </div>

    <div class="mb-6 grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach($dashboardStats as $stat)
            <div class="rounded-2xl border border-[#e9e2eb] bg-white p-4 shadow-[0_5px_20px_rgba(53,19,79,0.035)] transition hover:-translate-y-0.5 hover:shadow-[0_10px_26px_rgba(53,19,79,0.075)] sm:p-5">
                <div class="flex items-start justify-between gap-2">
                    <span class="text-[11px] font-semibold leading-4 text-[#8b818f]">
                        {{ $stat['label'] }}
                    </span>

                    <span class="grid h-9 w-9 place-items-center rounded-xl {{ $stat['tone'] === 'teal' ? 'bg-[#e4f7f3] text-[#087c73]' : ($stat['tone'] === 'gold' ? 'bg-[#fff4df] text-[#b5772c]' : 'bg-[#f3eafa] text-[#7c2f95]') }}">
                        <i class="bi {{ $stat['icon'] }}"></i>
                    </span>
                </div>

                <div class="mt-3 flex items-end gap-2">
                    <span class="text-[26px] font-bold leading-none tracking-[-0.05em] text-[#35134f]">
                        {{ $stat['value'] }}
                    </span>

                    <span class="pb-0.5 text-[10px] text-[#a096a3]">
                        {{ $stat['caption'] }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <section class="overflow-hidden rounded-2xl border border-[#e9e2eb] bg-white shadow-[0_5px_20px_rgba(53,19,79,0.035)]">
        <div class="flex flex-col justify-between gap-3 border-b border-[#eee8f0] p-5 sm:flex-row sm:items-center sm:p-6">
            <div>
                <h2 class="text-[15px] font-bold text-[#4c1d68]">
                    Lawatan terkini dan akan datang
                </h2>

                <p class="mt-1 text-[11px] text-[#9b929e]">
                    Ringkasan lawatan yang sedang diuruskan.
                </p>
            </div>

            <a
                href="{{ route('trips.index') }}"
                class="inline-flex items-center gap-1 text-[11px] font-bold text-[#7c2f95] hover:text-[#35134f]"
            >
                Lihat semua
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] border-collapse text-left">
                <thead>
                    <tr class="bg-[#fcfbfd] text-[10px] font-bold uppercase tracking-[0.1em] text-[#928995]">
                        <th class="px-5 py-3.5 sm:px-6">Kod lawatan</th>
                        <th class="px-4 py-3.5">Nama lawatan</th>
                        <th class="px-4 py-3.5">Tarikh mula</th>
                        <th class="px-4 py-3.5">Destinasi</th>
                        <th class="px-4 py-3.5">Peserta</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right sm:px-6">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#f0ebf2]">
                    @forelse($recentTrips as $trip)
                        <tr class="transition hover:bg-[#fdfaff]">
                            <td class="px-5 py-4 sm:px-6">
                                <a
                                    href="{{ route('trips.show', $trip) }}"
                                    class="font-mono text-xs font-semibold text-[#7c2f95] hover:text-[#35134f]"
                                >
                                    {{ $trip->trip_code }}
                                </a>
                            </td>

                            <td class="px-4 py-4 text-xs font-bold text-[#39313d]">
                                {{ $trip->title }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 text-xs text-[#6e6471]">
                                {{ $trip->start_date->format('d M Y') }}
                            </td>

                            <td class="px-4 py-4 text-xs text-[#6e6471]">
                                {{ $trip->destination }}
                            </td>

                            <td class="px-4 py-4 text-xs text-[#6e6471]">
                                {{ $trip->participants_count }}/{{ $trip->capacity }}
                            </td>

                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold {{ $statusClasses[$trip->status] ?? 'bg-[#f0eef1] text-[#68646b]' }}">
                                    {{ $statusLabels[$trip->status] ?? $trip->status }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-right sm:px-6">
                                <a
                                    href="{{ route('trips.show', $trip) }}"
                                    title="Lihat maklumat"
                                    class="inline-grid h-8 w-8 place-items-center rounded-lg border border-[#eadfe9] text-[#7c2f95] transition hover:bg-[#f8f1fa]"
                                >
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="7"
                                class="px-5 py-16 text-center sm:px-6"
                            >
                                <i class="bi bi-airplane text-4xl text-[#c4bac7]"></i>

                                <p class="mt-4 text-sm font-bold text-[#4c1d68]">
                                    Tiada lawatan lagi
                                </p>

                                <p class="mt-1 text-xs text-[#958d98]">
                                    Tambah lawatan pertama anda untuk bermula.
                                </p>

                                <a
                                    href="{{ route('trips.create') }}"
                                    class="mt-5 inline-flex h-10 items-center gap-2 rounded-xl bg-[#63247c] px-4 text-xs font-bold text-white hover:bg-[#35134f]"
                                >
                                    <i class="bi bi-plus-lg"></i>
                                    Tambah lawatan
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection