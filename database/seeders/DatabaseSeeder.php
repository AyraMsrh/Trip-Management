<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Participant;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Demo users
        |--------------------------------------------------------------------------
        */

        $admin = User::updateOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => Hash::make('admin123@'),
                'email_verified_at' => now(),
            ]
        );

        $staff = User::updateOrCreate(
            [
                'email' => 'staff@gmail.com',
            ],
            [
                'name' => 'Staff',
                'role' => 'staff',
                'password' => Hash::make('staff123@'),
                'email_verified_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Demo trips
        |--------------------------------------------------------------------------
        */

        $openTrip = Trip::updateOrCreate(
            [
                'trip_code' => 'TRP-DEMO-OPEN',
            ],
            [
                'title' => 'Japan Spring Escape',
                'destination' => 'Tokyo, Jepun',
                'start_date' => now()->addDays(30)->toDateString(),
                'end_date' => now()->addDays(36)->toDateString(),
                'capacity' => 5,
                'description' => 'Lawatan musim bunga ke Tokyo termasuk lawatan ke Asakusa, Shibuya, Mount Fuji dan kawasan sekitarnya.',
                'status' => 'open',
                'created_by' => $admin->id,
            ]
        );

        $draftTrip = Trip::updateOrCreate(
            [
                'trip_code' => 'TRP-DEMO-DRAFT',
            ],
            [
                'title' => 'Korea Winter Adventure',
                'destination' => 'Seoul, Korea Selatan',
                'start_date' => now()->addDays(75)->toDateString(),
                'end_date' => now()->addDays(82)->toDateString(),
                'capacity' => 20,
                'description' => 'Program lawatan musim sejuk ke Seoul dengan aktiviti bandar, makanan dan kebudayaan.',
                'status' => 'draft',
                'created_by' => $staff->id,
            ]
        );

        $closedTrip = Trip::updateOrCreate(
            [
                'trip_code' => 'TRP-DEMO-CLOSED',
            ],
            [
                'title' => 'Bali Family Holiday',
                'destination' => 'Bali, Indonesia',
                'start_date' => now()->addDays(10)->toDateString(),
                'end_date' => now()->addDays(15)->toDateString(),
                'capacity' => 30,
                'description' => 'Lawatan keluarga ke Bali dengan aktiviti pantai, budaya dan lawatan tempat menarik.',
                'status' => 'closed',
                'created_by' => $admin->id,
            ]
        );

        $completedTrip = Trip::updateOrCreate(
            [
                'trip_code' => 'TRP-DEMO-COMPLETED',
            ],
            [
                'title' => 'Singapore City Tour',
                'destination' => 'Singapura',
                'start_date' => now()->subDays(90)->toDateString(),
                'end_date' => now()->subDays(87)->toDateString(),
                'capacity' => 25,
                'description' => 'Lawatan bandar ke Singapura dengan kunjungan ke Marina Bay, Gardens by the Bay dan Sentosa.',
                'status' => 'completed',
                'created_by' => $staff->id,
            ]
        );

        $fullTrip = Trip::updateOrCreate(
            [
                'trip_code' => 'TRP-DEMO-FULL',
            ],
            [
                'title' => 'Bangkok Food Discovery',
                'destination' => 'Bangkok, Thailand',
                'start_date' => now()->addDays(45)->toDateString(),
                'end_date' => now()->addDays(49)->toDateString(),
                'capacity' => 3,
                'description' => 'Lawatan kulinari untuk meneroka makanan tempatan dan pasar terkenal di Bangkok.',
                'status' => 'open',
                'created_by' => $admin->id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Demo participants
        |--------------------------------------------------------------------------
        */

        $participants = [];

        $participants['ali'] = Participant::updateOrCreate(
            [
                'passport_number' => 'A12345678',
            ],
            [
                'full_name' => 'Ahmad Ali',
                'email' => 'ali@example.com',
                'phone' => '012-3456789',
                'passport_path' => null,
            ]
        );

        $participants['siti'] = Participant::updateOrCreate(
            [
                'passport_number' => 'A23456789',
            ],
            [
                'full_name' => 'Siti Aisyah',
                'email' => 'siti@example.com',
                'phone' => '013-4567890',
                'passport_path' => null,
            ]
        );

        $participants['nur'] = Participant::updateOrCreate(
            [
                'passport_number' => 'A34567890',
            ],
            [
                'full_name' => 'Nur Farhana',
                'email' => 'nur@example.com',
                'phone' => '014-5678901',
                'passport_path' => null,
            ]
        );

        $participants['daniel'] = Participant::updateOrCreate(
            [
                'passport_number' => 'A45678901',
            ],
            [
                'full_name' => 'Daniel Tan',
                'email' => 'daniel@example.com',
                'phone' => '016-6789012',
                'passport_path' => null,
            ]
        );

        $participants['farah'] = Participant::updateOrCreate(
            [
                'passport_number' => 'A56789012',
            ],
            [
                'full_name' => 'Farah Nabila',
                'email' => 'farah@example.com',
                'phone' => '017-7890123',
                'passport_path' => null,
            ]
        );

        $participants['hafiz'] = Participant::updateOrCreate(
            [
                'passport_number' => 'A67890123',
            ],
            [
                'full_name' => 'Mohd Hafiz',
                'email' => 'hafiz@example.com',
                'phone' => '018-8901234',
                'passport_path' => null,
            ]
        );

        $participants['julia'] = Participant::updateOrCreate(
            [
                'passport_number' => 'A78901234',
            ],
            [
                'full_name' => 'Julia Wong',
                'email' => 'julia@example.com',
                'phone' => '019-9012345',
                'passport_path' => null,
            ]
        );

        $participants['amir'] = Participant::updateOrCreate(
            [
                'passport_number' => 'A89012345',
            ],
            [
                'full_name' => 'Amir Hakim',
                'email' => 'amir@example.com',
                'phone' => '011-12345678',
                'passport_path' => null,
            ]
        );

        $participants['maya'] = Participant::updateOrCreate(
            [
                'passport_number' => 'A90123456',
            ],
            [
                'full_name' => 'Maya Sofea',
                'email' => 'maya@example.com',
                'phone' => '011-23456789',
                'passport_path' => null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Register participants for open trip
        |--------------------------------------------------------------------------
        */

        $this->registerParticipant(
            $openTrip,
            $participants['ali'],
            'paid',
            now()->subDays(5)
        );

        $this->registerParticipant(
            $openTrip,
            $participants['siti'],
            'partial',
            now()->subDays(4)
        );

        $this->registerParticipant(
            $openTrip,
            $participants['nur'],
            'pending',
            now()->subDays(3)
        );

        $this->registerParticipant(
            $openTrip,
            $participants['daniel'],
            'paid',
            now()->subDays(2)
        );

        /*
        |--------------------------------------------------------------------------
        | Register participants for closed trip
        |--------------------------------------------------------------------------
        */

        $this->registerParticipant(
            $closedTrip,
            $participants['farah'],
            'paid',
            now()->subDays(25)
        );

        $this->registerParticipant(
            $closedTrip,
            $participants['hafiz'],
            'partial',
            now()->subDays(24)
        );

        /*
        |--------------------------------------------------------------------------
        | Register participants for completed trip
        |--------------------------------------------------------------------------
        */

        $this->registerParticipant(
            $completedTrip,
            $participants['julia'],
            'paid',
            now()->subDays(100)
        );

        $this->registerParticipant(
            $completedTrip,
            $participants['amir'],
            'paid',
            now()->subDays(99)
        );

        $this->registerParticipant(
            $completedTrip,
            $participants['maya'],
            'paid',
            now()->subDays(98)
        );

        /*
        |--------------------------------------------------------------------------
        | Register participants for full trip
        |--------------------------------------------------------------------------
        */

        $this->registerParticipant(
            $fullTrip,
            $participants['farah'],
            'paid',
            now()->subDays(1)
        );

        $this->registerParticipant(
            $fullTrip,
            $participants['hafiz'],
            'partial',
            now()->subHours(20)
        );

        $this->registerParticipant(
            $fullTrip,
            $participants['julia'],
            'pending',
            now()->subHours(10)
        );

        /*
        |--------------------------------------------------------------------------
        | Activity logs
        |--------------------------------------------------------------------------
        */

        $this->createActivityLog(
            $admin->id,
            $openTrip->id,
            'created',
            'Lawatan Japan Spring Escape telah dicipta.'
        );

        $this->createActivityLog(
            $staff->id,
            $draftTrip->id,
            'created',
            'Lawatan Korea Winter Adventure telah dicipta sebagai draf.'
        );

        $this->createActivityLog(
            $admin->id,
            $openTrip->id,
            'participant_registered',
            'Ahmad Ali telah didaftarkan sebagai peserta.'
        );

        $this->createActivityLog(
            $admin->id,
            $openTrip->id,
            'participant_registered',
            'Siti Aisyah telah didaftarkan sebagai peserta.'
        );

        $this->createActivityLog(
            $staff->id,
            $completedTrip->id,
            'completed',
            'Lawatan Singapore City Tour telah selesai.'
        );

        $this->createActivityLog(
            $admin->id,
            $fullTrip->id,
            'capacity_reached',
            'Kapasiti maksimum Bangkok Food Discovery telah dicapai.'
        );
    }

    private function registerParticipant(
        Trip $trip,
        Participant $participant,
        string $paymentStatus,
        $registeredAt
    ): void {
        $trip->participants()->syncWithoutDetaching([
            $participant->id => [
                'payment_status' => $paymentStatus,
                'registered_at' => $registeredAt,
            ],
        ]);
    }

    private function createActivityLog(
        int $userId,
        int $tripId,
        string $action,
        string $description
    ): void {
        ActivityLog::updateOrCreate(
            [
                'trip_id' => $tripId,
                'action' => $action,
                'description' => $description,
            ],
            [
                'user_id' => $userId,
            ]
        );
    }
}