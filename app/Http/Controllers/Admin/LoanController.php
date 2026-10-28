<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100'], 'status' => ['nullable', Rule::in([Loan::STATUS_BORROWED, Loan::STATUS_RETURNED])]]);
        $search = trim($filters['q'] ?? '');
        $loans = Loan::query()->with(['user', 'book'])
            ->when($search !== '', fn ($query) => $query->where(fn ($query) => $query
                ->whereHas('user', fn ($user) => $user->whereLike('name', '%'.$search.'%')->orWhereLike('nrp_nim', '%'.$search.'%'))
                ->orWhereHas('book', fn ($book) => $book->whereLike('title', '%'.$search.'%'))))
            ->when(isset($filters['status']), fn ($query) => $query->where('status', $filters['status']))
            ->latest('borrowed_at')->latest('id')->paginate(15)->withQueryString();

        return view('admin.loans.index', ['loans' => $loans, 'search' => $search, 'selectedStatus' => $filters['status'] ?? '']);
    }

    public function create(): View
    {
        return view('admin.loans.create', ['members' => User::query()->where('role', User::ROLE_MEMBER)->where('is_active', true)->orderBy('name')->get(), 'books' => Book::query()->where('is_active', true)->orderBy('title')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['user_id' => ['required', 'integer', 'exists:users,id'], 'book_id' => ['required', 'integer', 'exists:books,id'], 'borrowed_at' => ['required', 'date'], 'due_at' => ['required', 'date', 'after_or_equal:borrowed_at']]);
        DB::transaction(function () use ($data): void {
            $member = User::query()->lockForUpdate()->findOrFail($data['user_id']);
            if ($member->role !== User::ROLE_MEMBER || ! $member->is_active) {
                throw ValidationException::withMessages(['user_id' => 'Anggota harus aktif.']);
            }
            $book = Book::query()->lockForUpdate()->findOrFail($data['book_id']);
            if (! $book->is_active) {
                throw ValidationException::withMessages(['book_id' => 'Buku harus aktif.']);
            }
            if ($book->available_stock < 1) {
                throw ValidationException::withMessages(['book_id' => 'Stok buku sedang habis.']);
            }
            $book->decrement('available_stock');
            Loan::create($data + ['status' => Loan::STATUS_BORROWED]);
        });

        return to_route('admin.loans.index')->with('success', 'Pinjaman berhasil dicatat.');
    }

    public function edit(Loan $loan): View
    {
        return view('admin.loans.edit', ['loan' => $loan->load(['user', 'book'])]);
    }

    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $data = $request->validate(['returned_at' => ['required', 'date', 'after_or_equal:'.$loan->borrowed_at->toDateString()]]);
        DB::transaction(function () use ($data, $loan): void {
            $lockedLoan = Loan::query()->lockForUpdate()->findOrFail($loan->id);
            if ($lockedLoan->status !== Loan::STATUS_BORROWED || $lockedLoan->returned_at !== null) {
                throw ValidationException::withMessages(['returned_at' => 'Pinjaman ini sudah dikembalikan.']);
            }
            $book = Book::query()->lockForUpdate()->findOrFail($lockedLoan->book_id);
            if ($book->available_stock >= $book->total_stock) {
                throw ValidationException::withMessages(['returned_at' => 'Stok buku sudah penuh; pengembalian dibatalkan.']);
            }
            $lockedLoan->update(['returned_at' => $data['returned_at'], 'status' => Loan::STATUS_RETURNED]);
            $book->increment('available_stock');
        });

        return to_route('admin.loans.index')->with('success', 'Pengembalian berhasil dicatat.');
    }
}
