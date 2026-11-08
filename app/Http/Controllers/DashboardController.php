<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalEntries = $user->entries()->count();
        $thisMonth    = $user->entries()
                            ->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->count();

        // Mood breakdown
        $moodData = $user->entries()
            ->select('mood', DB::raw('count(*) as total'))
            ->whereNotNull('mood')
            ->groupBy('mood')
            ->pluck('total', 'mood');

        // Entries per month (last 6 months)
        $monthlyData = $user->entries()
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Streak
        $streak = $this->calculateStreak($user);

        // Recent entries
        $recentEntries = $user->entries()->with('tags')->latest()->take(5)->get();

        // Kalender
        $calendarMonth = (int) request('cal_month', now()->month);
        $calendarYear  = (int) request('cal_year',  now()->year);

        // Ambil tanggal + mood dominan hari itu
        $entryDates = $user->entries()
        ->whereMonth('created_at', $calendarMonth)
        ->whereYear('created_at', $calendarYear)
        ->selectRaw('DATE(created_at) as date, COUNT(*) as count, MAX(mood) as mood')
        ->groupBy('date')
        ->get()
        ->keyBy('date'); // ['2026-05-03' => {date, count, mood}]

        $calendarDate   = \Carbon\Carbon::create($calendarYear, $calendarMonth, 1);
        $daysInMonth    = $calendarDate->daysInMonth;
        $firstDayOfWeek = $calendarDate->dayOfWeek;
        $prevMonth      = $calendarDate->copy()->subMonth();
        $nextMonth      = $calendarDate->copy()->addMonth();

        // Ambil entry per tanggal untuk keperluan link klik kalender
        $entriesByDate = $user->entries()
        ->whereMonth('created_at', $calendarMonth)
        ->whereYear('created_at', $calendarYear)
        ->selectRaw('DATE(created_at) as date, MIN(id) as first_entry_id')
        ->groupBy('date')
        ->pluck('first_entry_id', 'date');

        return view('dashboard', compact(
        'totalEntries', 'thisMonth', 'moodData',
        'monthlyData', 'streak', 'recentEntries',
        'entryDates', 'entriesByDate', 'calendarMonth', 'calendarYear',
        'daysInMonth', 'firstDayOfWeek', 'calendarDate',
        'prevMonth', 'nextMonth'
        ));
    }

    private function calculateStreak($user): int
    {
        $dates = $user->entries()
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderByDesc('date')
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d));

        $streak = 0;
        $check  = now()->startOfDay();

        foreach ($dates as $date) {
            if ($date->eq($check) || $date->eq($check->copy()->subDay())) {
                $streak++;
                $check = $date->copy()->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }
}