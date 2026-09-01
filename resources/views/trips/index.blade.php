@extends('layouts.main')

@section('title', 'Lawatan')

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

        $currentStatuses = ['draft', 'open'];
        $historyStatuses = ['closed', 'completed'];

        $activeTab = request('tab', 'current') === 'history'
            ? 'history'
            : 'current';

        $activeStatuses = $activeTab === 'history'
            ? $historyStatuses
            : $currentStatuses;

        $tripCollection = method_exists($trips, 'getCollection')
            ? $trips->getCollection()
            : collect($trips);

        $currentTripCount = $tripCollection
            ->whereIn('status', $currentStatuses)
            ->count();

        $historyTripCount = $tripCollection
            ->whereIn('status', $historyStatuses)
            ->count();

        $selectedStatus = request('status');

        if (!in_array($selectedStatus, $activeStatuses, true)) {
            $selectedStatus = '';
        }

        $visibleTrips = $tripCollection->filter(function ($trip) use (
            $activeStatuses,
            $selectedStatus
        ) {
            if (!in_array($trip->status, $activeStatuses, true)) {
                return false;
            }

            if ($selectedStatus && $trip->status !== $selectedStatus) {
                return false;
            }

            return true;
        });
    @endphp

    <div class="mb-6 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
        <div>
            <div class="mb-3 flex items-center gap-2 text-[11px] font-semibold text-[#948b98]">
                <a
                    href="{{ route('dashboard') }}"
                    class="text-[#7c2f95] transition hover:text-[#35134f]"
                >
                    Dashboard
                </a>

                <span class="text-[#c4bac7]">/</span>
                <span>Lawatan</span>
            </div>

            <h1 class="text-[clamp(1.8rem,3vw,2.55rem)] font-bold tracking-[-0.045em] text-[#35134f]">
                Lawatan
            </h1>

            <p class="mt-2 text-sm text-[#776c7b]">
                Urus semua program lawatan anda.
            </p>
        </div>

        <a
            href="{{ route('trips.create') }}"
            class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-[#078f83] px-4 text-xs font-bold text-white shadow-[0_7px_16px_rgba(6,155,140,0.16)] transition hover:-translate-y-0.5 hover:bg-[#04796e]"
        >
            <i class="bi bi-plus-lg"></i>
            Tambah lawatan
        </a>
    </div>

    <div class="mb-4 grid grid-cols-2 gap-3 lg:grid-cols-4">
        @foreach([
            ['Jumlah lawatan', $tripCollection->count(), 'bi-airplane', 'purple'],
            ['Lawatan terkini', $currentTripCount, 'bi-calendar-check', 'teal'],
            ['Sejarah lawatan', $historyTripCount, 'bi-archive', 'purple'],
            ['Peserta berdaftar', $tripCollection->sum('participants_count'), 'bi-people', 'gold'],
        ] as [$label, $value, $icon, $tone])
            <div class="rounded-2xl border border-[#e9e2eb] bg-white p-4 shadow-[0_5px_20px_rgba(53,19,79,0.035)] sm:p-5">
                <div class="flex items-start justify-between gap-2">
                    <span class="text-[11px] font-semibold text-[#8b818f]">
                        {{ $label }}
                    </span>

                    <span class="grid h-9 w-9 place-items-center rounded-xl
                        {{ $tone === 'teal'
                            ? 'bg-[#e4f7f3] text-[#087c73]'
                            : ($tone === 'gold'
                                ? 'bg-[#fff4df] text-[#b5772c]'
                                : 'bg-[#f3eafa] text-[#7c2f95]') }}"
                    >
                        <i class="bi {{ $icon }}"></i>
                    </span>
                </div>

                <div class="mt-3 text-[26px] font-bold leading-none tracking-[-0.05em] text-[#35134f]">
                    {{ $value }}
                </div>
            </div>
        @endforeach
    </div>

    <section class="overflow-hidden rounded-2xl border border-[#e9e2eb] bg-white shadow-[0_5px_20px_rgba(53,19,79,0.035)]">
        <div class="border-b border-[#eee8f0] px-5 pt-4 sm:px-6">
            <div class="flex gap-1">
                <a
                    href="{{ request()->fullUrlWithQuery([
                        'tab' => 'current',
                        'status' => null,
                        'page' => null,
                    ]) }}"
                    class="inline-flex items-center gap-2 rounded-t-xl border-b-2 px-3 py-3 text-xs font-bold
                        {{ $activeTab === 'current'
                            ? 'border-[#7c2f95] bg-[#f8f1fa] text-[#63247c]'
                            : 'border-transparent text-[#958d98] hover:text-[#63247c]' }}"
                >
                    <i class="bi bi-calendar-check"></i>
                    Lawatan terkini

                    <span class="rounded-full bg-white px-2 py-0.5 text-[10px] shadow-sm">
                        {{ $currentTripCount }}
                    </span>
                </a>

                <a
                    href="{{ request()->fullUrlWithQuery([
                        'tab' => 'history',
                        'status' => null,
                        'page' => null,
                    ]) }}"
                    class="inline-flex items-center gap-2 rounded-t-xl border-b-2 px-3 py-3 text-xs font-bold
                        {{ $activeTab === 'history'
                            ? 'border-[#7c2f95] bg-[#f8f1fa] text-[#63247c]'
                            : 'border-transparent text-[#958d98] hover:text-[#63247c]' }}"
                >
                    <i class="bi bi-archive"></i>
                    Sejarah lawatan

                    <span class="rounded-full bg-white px-2 py-0.5 text-[10px] shadow-sm">
                        {{ $historyTripCount }}
                    </span>
                </a>
            </div>
        </div>

        <div class="border-b border-[#eee8f0] bg-[#fcfbfd] p-4 sm:p-5">
            <form
                method="GET"
                action="{{ route('trips.index') }}"
                class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between"
            >
                <input
                    type="hidden"
                    name="tab"
                    value="{{ $activeTab }}"
                >

                <div class="flex items-center gap-2 text-xs font-bold text-[#4c1d68]">
                    <i class="bi bi-funnel text-[#7c2f95]"></i>
                    Tapis senarai
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:flex lg:items-center">
                    <select
                        name="status"
                        id="status"
                        class="h-10 min-w-[190px] rounded-xl border border-[#e5dfe8] bg-white px-3 text-xs text-[#403744] outline-none focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10"
                    >
                        <option value="">
                            Semua status
                        </option>

                        @if($activeTab === 'current')
                            <option
                                value="open"
                                @selected($selectedStatus === 'open')
                            >
                                Dibuka
                            </option>

                            <option
                                value="draft"
                                @selected($selectedStatus === 'draft')
                            >
                                Draf
                            </option>
                        @else
                            <option
                                value="closed"
                                @selected($selectedStatus === 'closed')
                            >
                                Ditutup
                            </option>

                            <option
                                value="completed"
                                @selected($selectedStatus === 'completed')
                            >
                                Selesai
                            </option>
                        @endif
                    </select>

                    <button
                        type="submit"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-[#7c2f95] px-4 text-xs font-bold text-white transition hover:bg-[#63247c]"
                    >
                        <i class="bi bi-funnel"></i>
                        Tapis
                    </button>

                    <a
                        href="{{ route('trips.index', ['tab' => $activeTab]) }}"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-[#ddd3e1] bg-white px-4 text-xs font-bold text-[#5a4c60] transition hover:border-[#bca5c5] hover:text-[#63247c]"
                    >
                        <i class="bi bi-x-lg"></i>
                        Kosongkan
                    </a>
                </div>
            </form>
        </div>

        <div class="flex items-center justify-between border-b border-[#eee8f0] px-5 py-4 sm:px-6">
            <div>
                <h2 class="text-[15px] font-bold text-[#4c1d68]">
                    {{ $activeTab === 'current' ? 'Lawatan terkini' : 'Sejarah lawatan' }}
                </h2>

                <p class="mt-1 text-[11px] text-[#9b929e]">
                    {{ $visibleTrips->count() }} rekod dipaparkan
                </p>
            </div>

            <span class="hidden items-center gap-1.5 text-[10px] font-semibold text-[#9b929e] sm:flex">
                <i class="bi bi-sliders text-[#7c2f95]"></i>
                Paparan padat
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[950px] border-collapse text-left">
                <thead>
                    <tr class="bg-[#fcfbfd] text-[10px] font-bold uppercase tracking-[0.1em] text-[#928995]">
                        <th class="px-5 py-3.5 sm:px-6">Lawatan</th>
                        <th class="px-4 py-3.5">Destinasi</th>
                        <th class="px-4 py-3.5">Tarikh</th>
                        <th class="px-4 py-3.5">Peserta</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right sm:px-6">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#f0ebf2]">
                    @forelse($visibleTrips as $trip)
                        @php
                            $percentage = $trip->capacity > 0
                                ? min(
                                    100,
                                    round(
                                        ($trip->participants_count / $trip->capacity) * 100
                                    )
                                )
                                : 0;
                        @endphp

                        <tr class="transition hover:bg-[#fdfaff]">
                            <td class="px-5 py-4 sm:px-6">
                                <a
                                    href="{{ route('trips.show', $trip) }}"
                                    class="text-sm font-bold text-[#63247c] hover:text-[#35134f]"
                                >
                                    {{ $trip->title }}
                                </a>

                                <div class="mt-1 font-mono text-[10px] text-[#9b929e]">
                                    {{ $trip->trip_code }}
                                </div>
                            </td>

                            <td class="px-4 py-4 text-xs text-[#6e6471]">
                                {{ $trip->destination }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4">
                                <div class="text-xs font-bold text-[#403744]">
                                    {{ $trip->start_date->format('d M Y') }}
                                </div>

                                <div class="mt-1 text-[10px] text-[#9b929e]">
                                    hingga {{ $trip->end_date->format('d M Y') }}
                                </div>
                            </td>

                            <td class="min-w-[150px] px-4 py-4">
                                <div class="mb-1 flex justify-between text-[11px] text-[#817783]">
                                    <span>
                                        {{ $trip->participants_count }}/{{ $trip->capacity }}
                                    </span>

                                    <span>{{ $percentage }}%</span>
                                </div>

                                <div class="h-1.5 overflow-hidden rounded-full bg-[#eeeaf0]">
                                    <div
                                        class="h-full rounded-full bg-[#0bb6a3]"
                                        style="width: {{ $percentage }}%"
                                    ></div>
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold {{ $statusClasses[$trip->status] ?? 'bg-[#f0eef1] text-[#68646b]' }}">
                                    {{ $statusLabels[$trip->status] ?? $trip->status }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-right sm:px-6">
                                <details class="relative inline-block text-left">
                                    <summary
                                        class="inline-grid h-8 w-8 cursor-pointer place-items-center rounded-lg border border-[#eadfe9] text-[#7c2f95] transition hover:bg-[#f8f1fa]"
                                        aria-label="Tindakan lawatan"
                                    >
                                        <i class="bi bi-three-dots"></i>
                                    </summary>

                                    <div class="absolute right-0 top-10 z-20 w-48 rounded-xl border border-[#e9e0eb] bg-white p-2 shadow-[0_18px_42px_rgba(53,19,79,0.14)]">
                                        <a
                                            href="{{ route('trips.show', $trip) }}"
                                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-[#504552] hover:bg-[#f8f1fa]"
                                        >
                                            <i class="bi bi-eye text-[#7c2f95]"></i>
                                            Lihat maklumat
                                        </a>

                                        <a
                                            href="{{ route('trips.edit', $trip) }}"
                                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-[#504552] hover:bg-[#f8f1fa]"
                                        >
                                            <i class="bi bi-pencil text-[#7c2f95]"></i>
                                            Kemaskini
                                        </a>

                                        @if(auth()->user()->isAdmin())
                                            <div class="my-1 border-t border-[#f0ebf2]"></div>

                                            <form
                                                method="POST"
                                                action="{{ route('trips.destroy', $trip) }}"
                                                onsubmit="return confirm('Adakah anda pasti mahu memadam lawatan ini?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-xs font-semibold text-[#b24c5a] hover:bg-[#fff3f3]"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                    Padam lawatan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center sm:px-6">
                                <i class="bi bi-airplane text-4xl text-[#c4bac7]"></i>

                                <p class="mt-4 text-sm font-bold text-[#4c1d68]">
                                    Tiada lawatan dijumpai
                                </p>

                                <p class="mt-1 text-xs text-[#958d98]">
                                    Cuba ubah carian atau tambah lawatan baharu.
                                </p>

                                <a
                                    href="{{ route('trips.create') }}"
                                    class="mt-4 inline-flex h-9 items-center gap-2 rounded-xl bg-[#078f83] px-4 text-xs font-bold text-white hover:bg-[#04796e]"
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

        @if($trips->hasPages())
            <div class="border-t border-[#eee8f0] px-5 py-4 sm:px-6">
                {{ $trips->appends(request()->query())->links() }}
            </div>
        @endif
    </section>
@endsection