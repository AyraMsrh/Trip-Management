<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Participant;
use App\Models\Trip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ParticipantRegistrationController extends Controller
{
    private const PAYMENT_LABELS = [
        'pending' => 'Belum bayar',
        'partial' => 'Sebahagian',
        'paid' => 'Sudah bayar',
    ];

    public function create(Trip $trip): View|RedirectResponse
    {
        if ($trip->status !== 'open') {
            return redirect()
                ->route('trips.show', $trip)
                ->with(
                    'error',
                    'Pendaftaran untuk lawatan ini telah ditutup.'
                );
        }

        if ($trip->isFull()) {
            return redirect()
                ->route('trips.show', $trip)
                ->with(
                    'error',
                    'Lawatan ini telah mencapai kapasiti maksimum.'
                );
        }

        return view('participants.create', [
            'trip' => $trip,
        ]);
    }

    public function store(
        Request $request,
        Trip $trip
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'full_name' => [
                    'required',
                    'string',
                    'max:150',
                ],
                'passport_number' => [
                    'required',
                    'string',
                    'max:50',
                ],
                'email' => [
                    'required',
                    'email',
                    'max:150',
                ],
                'phone' => [
                    'required',
                    'string',
                    'max:30',
                ],
                'passport' => [
                    'nullable',
                    'file',
                    'mimes:pdf,jpg,jpeg,png',
                    'max:5120',
                ],
                'payment_status' => [
                    'required',
                    'in:pending,partial,paid',
                ],
            ],
            [
                'full_name.required' =>
                    'Nama penuh peserta diperlukan.',

                'passport_number.required' =>
                    'Nombor pasport diperlukan.',

                'email.required' =>
                    'Alamat emel diperlukan.',

                'email.email' =>
                    'Sila masukkan alamat emel yang sah.',

                'phone.required' =>
                    'Nombor telefon diperlukan.',

                'passport.mimes' =>
                    'Fail pasport mestilah dalam format PDF, JPG, JPEG atau PNG.',

                'passport.max' =>
                    'Saiz fail pasport tidak boleh melebihi 5MB.',

                'payment_status.required' =>
                    'Sila pilih status bayaran.',
            ]
        );

        DB::transaction(function () use (
            $request,
            $validated,
            $trip
        ) {
            $lockedTrip = Trip::query()
                ->whereKey($trip->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedTrip->status !== 'open') {
                throw ValidationException::withMessages([
                    'trip' =>
                        'Pendaftaran untuk lawatan ini telah ditutup.',
                ]);
            }

            $registeredCount = $lockedTrip
                ->participants()
                ->count();

            if ($registeredCount >= $lockedTrip->capacity) {
                throw ValidationException::withMessages([
                    'trip' => 'Lawatan ini telah penuh.',
                ]);
            }

            $participant = Participant::query()
                ->where(
                    'passport_number',
                    $validated['passport_number']
                )
                ->first();

            if (
                $participant &&
                $lockedTrip
                    ->participants()
                    ->whereKey($participant->id)
                    ->exists()
            ) {
                throw ValidationException::withMessages([
                    'passport_number' =>
                        'Peserta ini telah didaftarkan untuk lawatan ini.',
                ]);
            }

            if (! $participant) {
                $participant = new Participant();
            }

            $participant->full_name = $validated['full_name'];
            $participant->passport_number =
                $validated['passport_number'];
            $participant->email = $validated['email'];
            $participant->phone = $validated['phone'];

            if ($request->hasFile('passport')) {
                $oldPassportPath = $participant->passport_path;

                $newPassportPath = $request
                    ->file('passport')
                    ->store('passports', 'public');

                $participant->passport_path = $newPassportPath;

                if ($oldPassportPath) {
                    Storage::disk('public')
                        ->delete($oldPassportPath);
                }
            }

            $participant->save();

            $lockedTrip->participants()->attach(
                $participant->id,
                [
                    'payment_status' =>
                        $validated['payment_status'],
                    'registered_at' => now(),
                ]
            );

            ActivityLog::create([
                'user_id' => Auth::id(),
                'trip_id' => $lockedTrip->id,
                'action' => 'participant_registered',
                'description' => "{$participant->full_name} telah didaftarkan sebagai peserta.",
            ]);
        });

        return redirect()
            ->route('trips.show', $trip)
            ->with(
                'success',
                'Peserta berjaya didaftarkan ke dalam lawatan.'
            );
    }

    public function updatePayment(
        Request $request,
        Trip $trip,
        Participant $participant
    ): RedirectResponse {
        $validated = $request->validate([
            'payment_status' => [
                'required',
                'in:pending,partial,paid',
            ],
        ]);

        $registration = $trip
            ->participants()
            ->whereKey($participant->id)
            ->first();

        if (! $registration) {
            abort(404);
        }

        $previousStatus = $registration->pivot->payment_status;

        $trip->participants()->updateExistingPivot(
            $participant->id,
            [
                'payment_status' =>
                    $validated['payment_status'],
            ]
        );

        if ($previousStatus !== $validated['payment_status']) {
            $fromLabel = self::PAYMENT_LABELS[$previousStatus] ?? $previousStatus;
            $toLabel = self::PAYMENT_LABELS[$validated['payment_status']] ?? $validated['payment_status'];

            ActivityLog::create([
                'user_id' => Auth::id(),
                'trip_id' => $trip->id,
                'action' => 'payment_status_updated',
                'description' => "Status bayaran {$participant->full_name} ditukar daripada {$fromLabel} kepada {$toLabel}.",
            ]);
        }

        return back()->with(
            'success',
            'Status bayaran berjaya dikemaskini.'
        );
    }

    public function destroy(
        Trip $trip,
        Participant $participant
    ): RedirectResponse {
        $registrationExists = $trip
            ->participants()
            ->whereKey($participant->id)
            ->exists();

        if (! $registrationExists) {
            abort(404);
        }

        $trip->participants()->detach($participant->id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'trip_id' => $trip->id,
            'action' => 'participant_removed',
            'description' => "Pendaftaran {$participant->full_name} telah dibatalkan.",
        ]);

        return back()->with(
            'success',
            'Pendaftaran peserta berjaya dibatalkan.'
        );
    }
}