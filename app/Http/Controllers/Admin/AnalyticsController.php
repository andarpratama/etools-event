<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function getDailyViews(Request $request)
    {
        $period = $request->get('period', 'daily'); // daily, weekly, monthly
        $days = $request->get('days', 30);
        
        switch ($period) {
            case 'weekly':
                return $this->getWeeklyViews($days);
            case 'monthly':
                return $this->getMonthlyViews($days);
            default:
                return $this->getDailyViewsData($days);
        }
    }

    private function getDailyViewsData($days)
    {
        $views = PageView::selectRaw('DATE(view_date) as date, COUNT(*) as views')
            ->where('view_date', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $labels = [];
        $data = [];
        
        $startDate = now()->subDays($days - 1);
        $endDate = now();
        
        $viewMap = $views->pluck('views', 'date')->toArray();
        
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $labels[] = $date->format('M d');
            $data[] = $viewMap[$dateStr] ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'total' => PageView::getTotalViews(),
            'today' => PageView::getTodayViews(),
            'period' => 'daily'
        ]);
    }

    private function getWeeklyViews($days)
    {
        $startDate = now()->subDays($days - 1)->startOfWeek();
        $endDate = now()->endOfWeek();
        
        $views = PageView::selectRaw('
                YEARWEEK(view_date, 1) as week,
                MIN(view_date) as week_start,
                COUNT(*) as views
            ')
            ->where('view_date', '>=', $startDate)
            ->where('view_date', '<=', $endDate)
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->get();

        $labels = [];
        $data = [];
        
        $viewMap = [];
        $weekStartMap = [];
        foreach ($views as $view) {
            $weekKey = $view->week;
            $viewMap[$weekKey] = $view->views;
            $weekStartMap[$weekKey] = $view->week_start;
        }
        
        $currentWeek = $startDate->copy();
        while ($currentWeek <= $endDate) {
            $weekKey = $currentWeek->format('oW'); // ISO week format (year + week number)
            $weekStart = $weekStartMap[$weekKey] ?? $currentWeek->format('Y-m-d');
            $weekEnd = Carbon::parse($weekStart)->endOfWeek();
            
            $labels[] = Carbon::parse($weekStart)->format('M d') . ' - ' . $weekEnd->format('M d');
            $data[] = $viewMap[$weekKey] ?? 0;
            
            $currentWeek->addWeek();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'total' => PageView::getTotalViews(),
            'today' => PageView::getTodayViews(),
            'period' => 'weekly'
        ]);
    }

    private function getMonthlyViews($days)
    {
        $startDate = now()->subDays($days - 1)->startOfMonth();
        $endDate = now()->endOfMonth();
        
        $views = PageView::selectRaw('
                DATE_FORMAT(view_date, "%Y-%m") as month,
                COUNT(*) as views
            ')
            ->where('view_date', '>=', $startDate)
            ->where('view_date', '<=', $endDate)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $labels = [];
        $data = [];
        
        $viewMap = $views->pluck('views', 'month')->toArray();
        
        $currentMonth = $startDate->copy();
        while ($currentMonth <= $endDate) {
            $monthKey = $currentMonth->format('Y-m');
            $labels[] = $currentMonth->format('M Y');
            $data[] = $viewMap[$monthKey] ?? 0;
            
            $currentMonth->addMonth();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'total' => PageView::getTotalViews(),
            'today' => PageView::getTodayViews(),
            'period' => 'monthly'
        ]);
    }
}

