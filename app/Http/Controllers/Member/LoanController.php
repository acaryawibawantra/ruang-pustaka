<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function index(Request $request): View
    {
        return view('member.loans.index', [
            'loans' => $request->user()->loans()->with('book')->latest('borrowed_at')->paginate(12),
        ]);
    }

    public function show(Request $request, Loan $loan): View
    {
        abort_unless($loan->user_id === $request->user()->id, 404);

        return view('member.loans.show', ['loan' => $loan->load('book')]);
    }
}
