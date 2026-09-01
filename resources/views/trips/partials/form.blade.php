<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label
            for="title"
            class="mb-2 block text-xs font-bold text-[#4c1d68]"
        >
            Nama lawatan
        </label>

        <input
            type="text"
            id="title"
            name="title"
            value="{{ old('title', $trip->title ?? '') }}"
            placeholder="Contoh: Japan Spring Escape"
            required
            class="h-11 w-full rounded-xl border border-[#ded9e2] bg-white px-3 text-sm text-[#302a33] outline-none transition placeholder:text-[#aaa4ad] focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10 @error('title') border-[#b24c5a] @enderror"
        >

        @error('title')
            <p class="mt-1.5 text-xs text-[#b24c5a]">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <label
            for="destination"
            class="mb-2 block text-xs font-bold text-[#4c1d68]"
        >
            Destinasi
        </label>

        <input
            type="text"
            id="destination"
            name="destination"
            value="{{ old('destination', $trip->destination ?? '') }}"
            placeholder="Contoh: Tokyo, Jepun"
            required
            class="h-11 w-full rounded-xl border border-[#ded9e2] bg-white px-3 text-sm text-[#302a33] outline-none transition placeholder:text-[#aaa4ad] focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10 @error('destination') border-[#b24c5a] @enderror"
        >

        @error('destination')
            <p class="mt-1.5 text-xs text-[#b24c5a]">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <label
            for="status"
            class="mb-2 block text-xs font-bold text-[#4c1d68]"
        >
            Status
        </label>

        <div class="relative">
            <select
                id="status"
                name="status"
                required
                class="h-11 w-full appearance-none rounded-xl border border-[#ded9e2] bg-white px-3 pr-10 text-sm text-[#302a33] outline-none transition focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10 @error('status') border-[#b24c5a] @enderror"
            >
                <option
                    value="draft"
                    @selected(old('status', $trip->status ?? 'draft') === 'draft')
                >
                    Draf
                </option>

                <option
                    value="open"
                    @selected(old('status', $trip->status ?? '') === 'open')
                >
                    Dibuka
                </option>

                <option
                    value="closed"
                    @selected(old('status', $trip->status ?? '') === 'closed')
                >
                    Ditutup
                </option>

                <option
                    value="completed"
                    @selected(old('status', $trip->status ?? '') === 'completed')
                >
                    Selesai
                </option>
            </select>

            <i class="bi bi-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-[#7c2f95]"></i>
        </div>

        @error('status')
            <p class="mt-1.5 text-xs text-[#b24c5a]">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <label
            for="start_date"
            class="mb-2 block text-xs font-bold text-[#4c1d68]"
        >
            Tarikh mula
        </label>

        <input
            type="date"
            id="start_date"
            name="start_date"
            value="{{ old('start_date', isset($trip) ? $trip->start_date->format('Y-m-d') : '') }}"
            required
            class="h-11 w-full rounded-xl border border-[#ded9e2] bg-white px-3 text-sm text-[#302a33] outline-none transition focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10 @error('start_date') border-[#b24c5a] @enderror"
        >

        @error('start_date')
            <p class="mt-1.5 text-xs text-[#b24c5a]">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <label
            for="end_date"
            class="mb-2 block text-xs font-bold text-[#4c1d68]"
        >
            Tarikh tamat
        </label>

        <input
            type="date"
            id="end_date"
            name="end_date"
            value="{{ old('end_date', isset($trip) ? $trip->end_date->format('Y-m-d') : '') }}"
            required
            class="h-11 w-full rounded-xl border border-[#ded9e2] bg-white px-3 text-sm text-[#302a33] outline-none transition focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10 @error('end_date') border-[#b24c5a] @enderror"
        >

        @error('end_date')
            <p class="mt-1.5 text-xs text-[#b24c5a]">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div>
        <label
            for="capacity"
            class="mb-2 block text-xs font-bold text-[#4c1d68]"
        >
            Kapasiti peserta
        </label>

        <input
            type="number"
            id="capacity"
            name="capacity"
            value="{{ old('capacity', $trip->capacity ?? 40) }}"
            min="1"
            max="500"
            required
            class="h-11 w-full rounded-xl border border-[#ded9e2] bg-white px-3 text-sm text-[#302a33] outline-none transition focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10 @error('capacity') border-[#b24c5a] @enderror"
        >

        <p class="mt-1.5 text-[11px] text-[#958d98]">
            Pendaftaran akan ditutup apabila kapasiti penuh.
        </p>

        @error('capacity')
            <p class="mt-1.5 text-xs text-[#b24c5a]">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <label
            for="description"
            class="mb-2 block text-xs font-bold text-[#4c1d68]"
        >
            Penerangan
        </label>

        <textarea
            id="description"
            name="description"
            rows="5"
            placeholder="Masukkan penerangan, itinerary atau nota dalaman..."
            class="w-full resize-y rounded-xl border border-[#ded9e2] bg-white px-3 py-3 text-sm text-[#302a33] outline-none transition placeholder:text-[#aaa4ad] focus:border-[#7c2f95] focus:ring-4 focus:ring-[#7c2f95]/10 @error('description') border-[#b24c5a] @enderror"
        >{{ old('description', $trip->description ?? '') }}</textarea>

        @error('description')
            <p class="mt-1.5 text-xs text-[#b24c5a]">
                {{ $message }}
            </p>
        @enderror
    </div>
</div>