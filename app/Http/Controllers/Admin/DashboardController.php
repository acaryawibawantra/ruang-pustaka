<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = today()->toDateString();
        $collection = Book::query()->where('is_active', true)
            ->selectRaw('COUNT(*) AS titles, COALESCE(SUM(total_stock), 0) AS copies, COALESCE(SUM(available_stock), 0) AS available')
            ->first();
        $outstanding = Loan::query()->where('status', Loan::STATUS_BORROWED)->whereNull('returned_at');
        $loans = (clone $outstanding)
            ->selectRaw('COUNT(*) AS active, COALESCE(SUM(CASE WHEN due_at < ? THEN 1 ELSE 0 END), 0) AS overdue', [$today])
            ->first();

        return view('admin.dashboard', [
            'metrics' => [
                'titles' => (int) $collection->titles,
                'copies' => (int) $collection->copies,
                'available' => (int) $collection->available,
                'active' => (int) $loans->active,
                'overdue' => (int) $loans->overdue,
            ],
            'overdueLoans' => $outstanding->where('due_at', '<', $today)
                ->with(['user:id,name,nrp_nim', 'book:id,title'])
                ->orderBy('due_at')->orderBy('id')->limit(5)->get(),
            'asOf' => now(),
        ]);
    }
}
