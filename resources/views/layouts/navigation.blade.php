<nav
    x-data="{ open: false }"
    class="border-b border-[#ece8ef] bg-white"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            {{-- Logo --}}
            <div class="flex items-center">
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center"
                >
                    <img
                        src="{{ asset('images/insaaf-logo.png') }}"
                        alt="Logo In Saaff Travel and Tours"
                        class="h-10 w-auto object-contain"
                    >
                </a>

                <div class="ml-8 hidden items-center gap-2 sm:flex">
                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-[#f3eafa] text-[#63247c]' : 'text-[#77717d] hover:bg-[#faf9fb] hover:text-[#63247c]' }}"
                    >
                        <i class="bi bi-grid-1x2-fill"></i>
                        Dashboard
                    </a>

                    <a
                        href="{{ route('trips.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition {{ request()->routeIs('trips.*') ? 'bg-[#f3eafa] text-[#63247c]' : 'text-[#77717d] hover:bg-[#faf9fb] hover:text-[#63247c]' }}"
                    >
                        <i class="bi bi-airplane"></i>
                        Lawatan
                    </a>
                </div>
            </div>

            {{-- User section --}}
            <div class="hidden items-center gap-3 sm:flex">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f3eafa] font-bold text-[#63247c]">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <div class="hidden flex-col leading-tight md:flex">
                    <span class="text-sm font-bold text-[#29252d]">
                        {{ Auth::user()->name }}
                    </span>

                    <span class="text-xs text-[#77717d]">
                        {{ Auth::user()->isAdmin() ? 'Admin' : 'Staff' }}
                    </span>
                </div>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg p-2 text-[#77717d] transition hover:bg-[#fde8ea] hover:text-[#dc3545]"
                        title="Log keluar"
                    >
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>

            {{-- Mobile menu button --}}
            <button
                type="button"
                @click="open = ! open"
                class="inline-flex items-center justify-center rounded-lg p-2 text-xl text-[#63247c] transition hover:bg-[#f3eafa] sm:hidden"
                aria-label="Buka menu"
            >
                <i
                    class="bi"
                    :class="open ? 'bi-x-lg' : 'bi-list'"
                ></i>
            </button>
        </div>
    </div>

    {{-- Mobile navigation --}}
    <div
        x-show="open"
        x-transition
        class="border-t border-[#ece8ef] bg-[#fcfbfd] sm:hidden"
    >
        <div class="space-y-1 px-4 py-3">
            <a
                href="{{ route('dashboard') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-[#f3eafa] text-[#63247c]' : 'text-[#77717d]' }}"
            >
                <i class="bi bi-grid-1x2-fill"></i>
                Dashboard
            </a>

            <a
                href="{{ route('trips.index') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold {{ request()->routeIs('trips.*') ? 'bg-[#f3eafa] text-[#63247c]' : 'text-[#77717d]' }}"
            >
                <i class="bi bi-airplane"></i>
                Lawatan
            </a>
        </div>

        <div class="border-t border-[#ece8ef] px-4 py-4">
            <div class="mb-3">
                <div class="font-semibold text-[#29252d]">
                    {{ Auth::user()->name }}
                </div>

                <div class="text-sm text-[#77717d]">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm font-semibold text-red-600 transition hover:bg-red-50"
                >
                    <i class="bi bi-box-arrow-right"></i>
                    Log keluar
                </button>
            </form>
        </div>
    </div>
</nav>