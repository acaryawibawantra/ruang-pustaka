<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $activeLoans = $user->loans()
            ->where('status', Loan::STATUS_BORROWED)
            ->whereNull('returned_at')
            ->with(['book.category'])
            ->orderBy('due_at')
            ->orderBy('id')
            ->get();

        $recentReturnedLoans = $user->loans()
            ->where('status', Loan::STATUS_RETURNED)
            ->whereNotNull('returned_at')
            ->with('book')
            ->latest('returned_at')
            ->latest('id')
            ->limit(5)
            ->get();

        $metrics = [
            'active' => $activeLoans->count(),
            'overdue' => $activeLoans->where('is_overdue', true)->count(),
            'approaching' => $activeLoans->where('is_approaching_due', true)->count(),
            'returned_total' => $user->loans()->where('status', Loan::STATUS_RETURNED)->count(),
        ];

        return view('member.dashboard', compact('activeLoans', 'recentReturnedLoans', 'metrics'));
    }
}
