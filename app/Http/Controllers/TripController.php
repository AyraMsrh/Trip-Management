<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Trip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TripController extends Controller
{
    private const STATUS_LABELS = [
        'draft' => 'Draf',
        'open' => 'Dibuka',
        'closed' => 'Ditutup',
        'completed' => 'Selesai',
    ];

    public function index(Request $request): View
    {
        $query = Trip::query()
            ->with('creator')
            ->withCount('participants');

        if ($request->filled('search')) {
            $search = $request
                ->string('search')
                ->toString();

            $query->where(function ($query) use ($search) {
                $query
                    ->where('trip_code', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->string('status')->toString()
            );
        }

        $trips = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('trips.index', [
            'trips' => $trips,
        ]);
    }

    public function create(): View
    {
        return view('trips.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'title' => [
                    'required',
                    'string',
                    'max:150',
                ],
                'destination' => [
                    'required',
                    'string',
                    'max:150',
                ],
                'start_date' => [
                    'required',
                    'date',
                ],
                'end_date' => [
                    'required',
                    'date',
                    'after_or_equal:start_date',
                ],
                'capacity' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:500',
                ],
                'status' => [
                    'required',
                    'in:draft,open,closed,completed',
                ],
                'description' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],
            ],
            [
                'title.required' =>
                    'Nama lawatan diperlukan.',

                'destination.required' =>
                    'Destinasi diperlukan.',

                'start_date.required' =>
                    'Tarikh mula diperlukan.',

                'end_date.required' =>
                    'Tarikh tamat diperlukan.',

                'end_date.after_or_equal' =>
                    'Tarikh tamat mestilah selepas atau sama dengan tarikh mula.',

                'capacity.required' =>
                    'Kapasiti diperlukan.',

                'capacity.integer' =>
                    'Kapasiti mestilah nombor.',

                'capacity.min' =>
                    'Kapasiti mestilah sekurang-kurangnya 1.',

                'status.required' =>
                    'Status lawatan diperlukan.',
            ]
        );

        $validated['trip_code'] = $this->generateTripCode();
        $validated['created_by'] = Auth::id();

        $trip = Trip::create($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'trip_id' => $trip->id,
            'action' => 'trip_created',
            'description' => "Lawatan {$trip->title} telah dicipta.",
        ]);

        return redirect()
            ->route('trips.index')
            ->with('success', 'Lawatan berjaya ditambah.');
    }

    public function show(Trip $trip): View
    {
        $trip->load([
            'creator',
            'participants',
            'activityLogs.user',
        ]);

        return view('trips.show', [
            'trip' => $trip,
        ]);
    }

    public function edit(Trip $trip): View
    {
        return view('trips.edit', [
            'trip' => $trip,
        ]);
    }

    public function update(
        Request $request,
        Trip $trip
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'title' => [
                    'required',
                    'string',
                    'max:150',
                ],
                'destination' => [
                    'required',
                    'string',
                    'max:150',
                ],
                'start_date' => [
                    'required',
                    'date',
                ],
                'end_date' => [
                    'required',
                    'date',
                    'after_or_equal:start_date',
                ],
                'capacity' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:500',
                ],
                'status' => [
                    'required',
                    'in:draft,open,closed,completed',
                ],
                'description' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],
            ],
            [
                'end_date.after_or_equal' =>
                    'Tarikh tamat mestilah selepas atau sama dengan tarikh mula.',
            ]
        );

        $registeredCount = $trip
            ->participants()
            ->count();

        if ($validated['capacity'] < $registeredCount) {
            return back()
                ->withInput()
                ->withErrors([
                    'capacity' =>
                        "Kapasiti tidak boleh kurang daripada jumlah peserta berdaftar ({$registeredCount}).",
                ]);
        }

        $previousStatus = $trip->status;

        $trip->update($validated);

        if ($previousStatus !== $trip->status) {
            $fromLabel = self::STATUS_LABELS[$previousStatus] ?? $previousStatus;
            $toLabel = self::STATUS_LABELS[$trip->status] ?? $trip->status;

            $description = "Status lawatan ditukar daripada {$fromLabel} kepada {$toLabel}.";
        } else {
            $description = 'Maklumat lawatan telah dikemaskini.';
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'trip_id' => $trip->id,
            'action' => 'trip_updated',
            'description' => $description,
        ]);

        return redirect()
            ->route('trips.show', $trip)
            ->with('success', 'Maklumat lawatan berjaya dikemaskini.');
    }

    public function destroy(Trip $trip): RedirectResponse
    {
        if (! Auth::user()->isAdmin()) {
            abort(
                403,
                'Hanya Admin boleh memadam lawatan.'
            );
        }

        if ($trip->participants()->exists()) {
            return back()->with(
                'error',
                'Lawatan yang mempunyai peserta tidak boleh dipadam.'
            );
        }

        $trip->delete();

        return redirect()
            ->route('trips.index')
            ->with('success', 'Lawatan berjaya dipadam.');
    }

    private function generateTripCode(): string
    {
        do {
            $code = 'TRP-'
                . now()->format('Ym')
                . '-'
                . Str::upper(Str::random(5));
        } while (Trip::where('trip_code', $code)->exists());

        return $code;
    }
}