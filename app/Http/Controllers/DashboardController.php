<?php

namespace App\Http\Controllers;

use App\Models\AgentRecommendation;
use App\Models\Airline;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\FlightStatus;
use App\Models\User;
use App\Supports\Pages\DashboardPage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        // $bookingAnalytics = collect(range(29, 0))
        //     ->map(function ($day) {

        //         $date = Carbon::now()->subDays($day);

        //         return [

        //             'date' => $date->format('d M'),

        //             'total' => Booking::whereDate(
        //                 'created_at',
        //                 $date
        //             )->count(),

        //         ];
        //     });

        // $flightStatusChart = [

        //     'on_time' => FlightStatus::where(
        //         'status',
        //         'on_time'
        //     )->count(),

        //     'delayed' => FlightStatus::where(
        //         'status',
        //         'delayed'
        //     )->count(),

        //     'cancelled' => FlightStatus::where(
        //         'status',
        //         'cancelled'
        //     )->count(),

        // ];
        return inertia('Dashboard', [
            'pageSettings' => DashboardPage::index(),

            // 'statistics' => [
            //     // Flight
            //     'total_flights' => Flight::count(),

            //     // Booking
            //     'total_bookings' => Booking::count(),

            //     // User
            //     'total_users' => User::count(),

            //     // Delay
            //     'delayed_flights' => FlightStatus::where(
            //         'status',
            //         'delayed'
            //     )->count(),

            //     // AI Recommendation
            //     'ai_recommendations' => AgentRecommendation::count(),

            //     // Airline
            //     'total_airlines' => Airline::count(),
            // ],
            // 'bookingAnalytics' => $bookingAnalytics,

            // 'flightStatusChart' => $flightStatusChart,
        ]);
    }
}
