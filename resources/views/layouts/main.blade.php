<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        @yield('title', 'Sistem Pengurusan Lawatan')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    {{-- Bootstrap Icons--}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    @stack('styles')
    <style>
        #app-sidebar {
            width: 248px;
        }
        #app-shell[data-sidebar-collapsed="true"] #app-sidebar {
            width: 84px;
        }
        #app-shell[data-sidebar-collapsed="true"] .sidebar-label {
            display: none;
        }
        #app-shell[data-sidebar-collapsed="true"] .sidebar-link {
            justify-content: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        #app-shell[data-sidebar-collapsed="true"] .sidebar-info {
            display: none;
        }
        summary {
            list-style: none;
        }
        summary::-webkit-details-marker {
            display: none;
        }
        @media (max-width: 1023px) {
            #app-sidebar {
                width: 278px;
            }
            #app-shell[data-sidebar-collapsed="true"] #app-sidebar {
                width: 278px;
            }
            #app-shell[data-sidebar-collapsed="true"] .sidebar-label,
            #app-shell[data-sidebar-collapsed="true"] .sidebar-info,
            #app-shell[data-sidebar-collapsed="true"] .sidebar-link {
                display: flex;
            }
            #app-shell[data-sidebar-collapsed="true"] .sidebar-link {
                justify-content: flex-start;
                padding-left: 0.875rem;
                padding-right: 0.875rem;
            }
        }
    </style>
</head>

<body class="min-h-screen overflow-x-hidden bg-[#f8f6f9] text-[#302a33] antialiased">
    @php
        $navItems = [
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'pattern' => 'dashboard',
                'icon' => 'bi-grid-1x2-fill',
            ],
            [
                'label' => 'Lawatan',
                'route' => 'trips.index',
                'pattern' => 'trips.*',
                'icon' => 'bi-airplane',
            ],
        ];
    @endphp 
    <div
        id="app-shell"
        data-sidebar-collapsed="false"
        class="min-h-screen"
        >
        <div
            id="sidebar-backdrop"
            class="fixed inset-0 z-30 hidden bg-[#241033]/40 backdrop-blur-[2px] lg:hidden"
        ></div>
        <div class="flex min-h-screen">
            <aside
                id="app-sidebar"
                class="fixed inset-y-0 left-0 z-40 flex -translate-x-full flex-col bg-[#241033] text-white shadow-[14px_0_34px_rgba(53,19,79,0.12)] transition-all duration-300 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
            >
            <div class="flex min-h-[148px] items-center justify-center border-b border-white/10 bg-white px-4">
                <a
                    href="{{ route('dashboard') }}"
                    class="flex w-full items-center justify-center gap-3 lg:justify-start"
                >
                    <img
                        src="{{ asset('images/insaaf-logo.png') }}"
                        alt="Logo In Saaff Travel and Tours"
                        class="block h-[104px] w-full max-w-[178px] object-contain"
                    >
                </a>
            </div>

            {{-- Navigation --}}
            <nav 
                class="flex flex-1 flex-col gap-1.5 px-3 py-7">
                <div
                    class="sidebar-label mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.17em] text-white/40">
                    Menu utama
                </div>

                @foreach($navItems as $item)
                <a
                    href="{{ route($item['route']) }}"
                    class="sidebar-link flex min-h-11 items-center gap-3 rounded-xl px-3.5 text-left text-[13px] font-semibold transition
                                {{ request()->routeIs($item['pattern'])
                                    ? 'bg-white/[0.14] text-white shadow-[inset_3px_0_0_#0bb6a3]'
                                    : 'text-white/65 hover:bg-white/10 hover:text-white' }}"
                >
                    <i class="bi {{ $item['icon'] }} shrink-0 text-[18px]"></i>
                    <span class="sidebar-label">
                        {{ $item['label'] }}
                    </span>

                    @if($item['label'] === 'Lawatan')
                        <span class="sidebar-label ml-auto rounded-full bg-[#0bb6a3]/20 px-2 py-0.5 text-[10px] font-bold text-[#7de0d4]">
                            {{ $sidebarTripCount ?? '' }}
                        </span>
                    @endif
                </a>
                @endforeach
            </nav>
        </aside>

        {{-- Main content --}}
        <main class="min-w-0 flex-1">
                <header class="sticky top-0 z-20 flex min-h-[74px] items-center justify-between border-b border-[#e8e1eb] bg-white/95 px-4 shadow-[0_5px_20px_rgba(53,19,79,0.035)] backdrop-blur-md sm:px-7 lg:px-9">
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            id="sidebarToggle"
                            aria-label="Buka atau kecilkan menu"
                            aria-controls="app-sidebar"
                            class="grid h-10 w-10 place-items-center rounded-xl text-[#63247c] transition hover:bg-[#f3eafa] focus:outline-none focus:ring-4 focus:ring-[#7c2f95]/10"
                        >
                            <i class="bi bi-list text-xl"></i>
                        </button>
                    </div>
                    <div class="flex items-center gap-3">
                        <form
                            method="GET"
                            action="{{ route('trips.index') }}"
                            class="relative w-[min(52vw,295px)]"
                        >
                            <i class="bi bi-search pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[#958d98]"></i>
                            <input
                                type="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari kod, nama atau destinasi"
                                aria-label="Cari lawatan"
                                class="h-10 w-full rounded-full border border-[#e5dfe8] bg-[#faf8fb] pl-10 pr-4 text-[12px] outline-none placeholder:text-[#aaa2ad] focus:border-[#8b4d9d] focus:bg-white focus:ring-4 focus:ring-[#7c2f95]/10"
                            >
                            @if(request('tab'))
                                <input
                                    type="hidden"
                                    name="tab"
                                    value="{{ request('tab') }}"
                                >
                            @endif
                        </form>
                        <button
                            type="button"
                            aria-label="Notifikasi"
                            class="relative grid h-10 w-10 place-items-center rounded-full text-[#6b5b70] transition hover:bg-[#f3eafa] hover:text-[#63247c]"
                        >
                            <i class="bi bi-bell text-lg"></i>
                            <span class="absolute right-[9px] top-[8px] h-1.5 w-1.5 rounded-full bg-[#0bb6a3] ring-2 ring-white"></span>
                        </button>
                        <details class="relative">
                            <summary class="flex cursor-pointer items-center gap-2 rounded-full p-1 transition hover:bg-[#f3eafa] sm:gap-2.5">
                                <span class="grid h-9 w-9 place-items-center rounded-full bg-[#f0ddec] text-[12px] font-extrabold text-[#74336e]">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </span>
                                <span class="hidden text-left sm:block">
                                    <span class="block text-[12px] font-bold text-[#302a33]">
                                        {{ auth()->user()->name }}
                                    </span>
                                    <span class="block text-[10px] text-[#9b929e]">
                                        {{ auth()->user()->isAdmin() ? 'Admin' : 'Staff' }}
                                    </span>
                                </span>
                                <i class="bi bi-chevron-down hidden text-[11px] text-[#8d8290] sm:block"></i>
                            </summary>
                            <div class="absolute right-0 top-[calc(100%+9px)] z-30 w-48 rounded-2xl border border-[#e9e0eb] bg-white p-2 shadow-[0_18px_42px_rgba(53,19,79,0.14)]">
                                <a
                                    href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-[12px] font-semibold text-[#504552] hover:bg-[#f8f1fa]"
                                >
                                    <i class="bi bi-person-circle text-[#7c2f95]"></i>
                                    Profil saya
                                </a>
                                <div class="my-1 border-t border-[#f0ebf2]"></div>
                                <form
                                    method="POST"
                                    action="{{ route('logout') }}"
                                >
                                    @csrf
                                    <button
                                        type="submit"
                                        class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2.5 text-left text-[12px] font-semibold text-[#b24c5a] hover:bg-[#fff3f3]"
                                    >
                                        <i class="bi bi-box-arrow-right"></i>
                                        Log keluar
                                    </button>
                                </form>
                            </div>
                        </details>
                    </div>
                </header>
                <section class="mx-auto max-w-[1450px] px-4 pb-20 pt-6 sm:px-7 sm:pt-8 lg:px-9">
                    @if(session('success'))
                        <div
                            role="alert"
                            class="mb-5 flex items-start justify-between gap-4 rounded-2xl border border-[#b9e7dc] bg-[#e9f9f4] px-4 py-3 text-[12px] text-[#087c73]"
                        >
                            <div class="flex items-start gap-2.5">
                                <i class="bi bi-check-circle-fill mt-0.5"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button
                                type="button"
                                data-dismiss-alert
                                aria-label="Tutup notis"
                                class="text-[#087c73]/60 hover:text-[#087c73]"
                            >
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div
                            role="alert"
                            class="mb-5 flex items-start justify-between gap-4 rounded-2xl border border-[#f0c9ce] bg-[#fff3f3] px-4 py-3 text-[12px] text-[#ad5663]"
                        >
                            <div class="flex items-start gap-2.5">
                                <i class="bi bi-exclamation-circle-fill mt-0.5"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button
                                type="button"
                                data-dismiss-alert
                                aria-label="Tutup notis"
                                class="text-[#ad5663]/60 hover:text-[#ad5663]"
                            >
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif
                    @if($errors->any())
                        <div
                            role="alert"
                            class="mb-5 rounded-2xl border border-[#f0c9ce] bg-[#fff3f3] px-4 py-4 text-[12px] text-[#ad5663]"
                        >
                            <div class="flex items-center gap-2 font-bold">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                Sila betulkan perkara berikut:
                            </div>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')
                </section>
            </main>
        </div>
    </div>
    <script>
        (() => {
            const shell = document.getElementById('app-shell');
            const sidebar = document.getElementById('app-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const toggle = document.getElementById('sidebarToggle');
            const collapseButton = document.getElementById('sidebarCollapseButton');
            if (!shell || !sidebar || !backdrop || !toggle) {
                return;
            }
            const isDesktop = () => window.matchMedia('(min-width: 1024px)').matches;
            const setMobileSidebar = (open) => {
                sidebar.classList.toggle('translate-x-0', open);
                sidebar.classList.toggle('-translate-x-full', !open);
                backdrop.classList.toggle('hidden', !open);
                document.body.classList.toggle('overflow-hidden', open && !isDesktop());
            };
            const setCollapsed = (collapsed) => {
                shell.dataset.sidebarCollapsed = collapsed ? 'true' : 'false';
                localStorage.setItem('sidebar-collapsed', collapsed ? 'true' : 'false');
            };
            const toggleSidebar = () => {
                if (isDesktop()) {
                    setCollapsed(shell.dataset.sidebarCollapsed !== 'true');
                } else {
                    const isOpen = sidebar.classList.contains('translate-x-0');
                    setMobileSidebar(!isOpen);
                }
            };
            const savedCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isDesktop()) {
                setCollapsed(savedCollapsed);
            }
            toggle.addEventListener('click', toggleSidebar);
            collapseButton?.addEventListener('click', () => {
                setCollapsed(shell.dataset.sidebarCollapsed !== 'true');
            });
            backdrop.addEventListener('click', () => setMobileSidebar(false));
            window.addEventListener('resize', () => {
                if (isDesktop()) {
                    setMobileSidebar(false);
                    setCollapsed(localStorage.getItem('sidebar-collapsed') === 'true');
                }
            });
            document.querySelectorAll('[data-dismiss-alert]').forEach((button) => {
                button.addEventListener('click', () => {
                    button.closest('[role="alert"]')?.remove();
                });
            });
        })();
    </script>
</body>
</html>