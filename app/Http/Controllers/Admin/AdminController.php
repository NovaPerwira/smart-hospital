<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DailyReport;
use App\Models\FollowUpRule;
use App\Models\NotificationLog;
use App\Models\Payment;
use App\Models\ScheduledFollowUp;
use App\Models\Treatment;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct(private BookingService $bookingService)
    {
    }

    // ─── Dashboard ───────────────────────────────────────────────────────────

    public function dashboard()
    {
        $today = now()->toDateString();

        $stats = [
            'total_today' => Booking::where('booking_date', $today)->count(),
            'confirmed' => Booking::where('booking_date', $today)->where('status', 'confirmed')->count(),
            'completed' => Booking::where('booking_date', $today)->where('status', 'completed')->count(),
            'no_show' => Booking::where('booking_date', $today)->where('status', 'no_show')->count(),
            'cancelled' => Booking::where('booking_date', $today)->where('status', 'cancelled')->count(),
            'revenue_today' => Payment::whereDate('paid_at', $today)->where('status', 'paid')->sum('amount'),
            'pending_followups' => ScheduledFollowUp::where('status', 'pending')->count(),
            'notifications_sent' => NotificationLog::whereDate('created_at', $today)->count(),
        ];

        $recentBookings = Booking::with(['doctor', 'treatment', 'slot'])
            ->whereDate('booking_date', $today)
            ->latest()
            ->take(8)
            ->get();

        $upcomingFollowUps = ScheduledFollowUp::with(['booking'])
            ->where('status', 'pending')
            ->where('dispatch_at', '>=', now())
            ->orderBy('dispatch_at')
            ->take(5)
            ->get();

        $latestReport = DailyReport::latest('report_date')->first();

        $weeklyBookings = Booking::select(
            DB::raw('DATE(booking_date) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw("SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as completed"),
            DB::raw("SUM(CASE WHEN status='no_show' THEN 1 ELSE 0 END) as no_show")
        )
            ->where('booking_date', '>=', now()->subDays(6)->toDateString())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentBookings',
            'upcomingFollowUps',
            'latestReport',
            'weeklyBookings'
        ));
    }

    // ─── Bookings ─────────────────────────────────────────────────────────────

    public function bookings(Request $request)
    {
        $query = Booking::with(['doctor', 'treatment', 'slot'])
            ->latest('booking_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->where('booking_date', $request->date);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('patient_name', 'like', "%{$request->search}%")
                    ->orWhere('booking_code', 'like', "%{$request->search}%")
                    ->orWhere('patient_phone', 'like', "%{$request->search}%");
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.bookings', compact('bookings'));
    }

    public function bookingDetail(Booking $booking)
    {
        $booking->load(['doctor', 'treatment', 'slot', 'notifications', 'scheduledFollowUps.rule']);
        return view('admin.booking-detail', compact('booking'));
    }

    public function markArrived(Booking $booking)
    {
        $booking->update(['arrived_at' => now()]);
        return back()->with('success', "✅ {$booking->patient_name} marked as arrived.");
    }

    public function markCompleted(Booking $booking)
    {
        $this->bookingService->completeBooking($booking);
        return back()->with('success', "✅ Booking {$booking->booking_code} marked as completed. Follow-up reminders scheduled.");
    }

    // ─── Follow-Up Rules ──────────────────────────────────────────────────────

    public function followUpRules()
    {
        $rules = FollowUpRule::with('treatment')->latest()->get();
        $treatments = Treatment::all();
        return view('admin.follow-up-rules', compact('rules', 'treatments'));
    }

    public function storeFollowUpRule(Request $request)
    {
        $data = $request->validate([
            'treatment_id' => 'required|exists:treatments,id',
            'interval_value' => 'required|integer|min:1',
            'interval_unit' => 'required|in:minutes,hours,days,weeks,months',
            'channel' => 'required|in:whatsapp,email',
            'message_template' => 'required|string',
            'is_active' => 'boolean',
        ]);
        $data['trigger_event'] = 'completed';
        $data['is_active'] = $request->boolean('is_active', true);

        FollowUpRule::create($data);
        return back()->with('success', '✅ Follow-up rule created successfully.');
    }

    public function toggleFollowUpRule(FollowUpRule $rule)
    {
        $rule->update(['is_active' => !$rule->is_active]);
        $state = $rule->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Rule {$state} successfully.");
    }

    public function destroyFollowUpRule(FollowUpRule $rule)
    {
        $rule->delete();
        return back()->with('success', '🗑️ Rule deleted.');
    }

    // ─── Daily Reports ────────────────────────────────────────────────────────

    public function reports()
    {
        $reports = DailyReport::latest('report_date')->paginate(20);
        return view('admin.reports', compact('reports'));
    }

    public function reportDetail(DailyReport $report)
    {
        return view('admin.report-detail', compact('report'));
    }

    // ─── Notification Logs ────────────────────────────────────────────────────

    public function notificationLogs(Request $request)
    {
        $query = NotificationLog::with('booking')->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $logs = $query->paginate(20)->withQueryString();
        return view('admin.notification-logs', compact('logs'));
    }

    // ─── Scheduled Follow-Ups ─────────────────────────────────────────────────

    public function scheduledFollowUps(Request $request)
    {
        $query = ScheduledFollowUp::with(['booking', 'rule.treatment'])->latest('dispatch_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $followUps = $query->paginate(20)->withQueryString();
        return view('admin.scheduled-follow-ups', compact('followUps'));
    }
}
