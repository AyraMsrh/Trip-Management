<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalTrips = Trip::count();

        $upcomingTrips = Trip::query()
            ->whereDate('start_date', '>=', today())
            ->whereIn('status', ['open', 'draft'])
            ->count();

        $totalParticipants = Participant::count();

        $pendingPayments = DB::table('trip_participant')
            ->where('payment_status', 'pending')
            ->count();

        $recentTrips = Trip::query()
            ->withCount('participants')
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'totalTrips' => $totalTrips,
            'upcomingTrips' => $upcomingTrips,
            'totalParticipants' => $totalParticipants,
            'pendingPayments' => $pendingPayments,
            'recentTrips' => $recentTrips,
        ]);
    }
}