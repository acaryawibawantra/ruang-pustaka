<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100'], 'category' => ['nullable', 'integer', 'exists:categories,id'], 'status' => ['nullable', Rule::in(['active', 'inactive'])]]);
        $search = trim($filters['q'] ?? '');
        $books = Book::query()->with('category')->when($search !== '', fn ($query) => $query->where(fn ($query) => $query->whereLike('title', '%'.$search.'%')->orWhereLike('author', '%'.$search.'%')))->when($filters['category'] ?? null, fn ($query, $category) => $query->where('category_id', $category))->when(isset($filters['status']), fn ($query) => $query->where('is_active', $filters['status'] === 'active'))->orderBy('title')->orderBy('id')->paginate(15)->withQueryString();

        return view('admin.books.index', ['books' => $books, 'categories' => Category::query()->orderBy('name')->get(), 'search' => $search, 'selectedCategory' => $filters['category'] ?? null, 'selectedStatus' => $filters['status'] ?? '']);
    }

    public function create(): View
    {
        return view('admin.books.create', ['book' => new Book, 'categories' => Category::query()->orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $coverPath = $this->storeCover($request);
        $data['cover_image'] = $coverPath;
        try {
            DB::transaction(fn () => Book::create($data));
        } catch (Throwable $exception) {
            $this->deleteCover($coverPath);
            throw $exception;
        }

        return to_route('admin.books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book): View
    {
        return view('admin.books.edit', ['book' => $book, 'categories' => Category::query()->orderBy('name')->get()]);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $data = $this->validatedData($request, $book);
        $newCoverPath = $this->storeCover($request);
        $oldCoverPath = $book->cover_image;
        try {
            DB::transaction(function () use ($book, $data, $newCoverPath): void {
                $lockedBook = Book::query()->lockForUpdate()->findOrFail($book->id);
                $activeLoans = $lockedBook->loans()->where('status', Loan::STATUS_BORROWED)->whereNull('returned_at')->count();
                if ($data['available_stock'] < $activeLoans) {
                    throw ValidationException::withMessages(['available_stock' => "Stok tersedia tidak boleh kurang dari {$activeLoans} pinjaman aktif."]);
                }
                $lockedBook->update($data + ['cover_image' => $newCoverPath ?: $lockedBook->cover_image]);
            });
        } catch (Throwable $exception) {
            $this->deleteCover($newCoverPath);
            throw $exception;
        }
        if ($newCoverPath && $oldCoverPath && $newCoverPath !== $oldCoverPath) {
            $this->deleteCover($oldCoverPath);
        }

        return to_route('admin.books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function deactivate(Book $book): RedirectResponse
    {
        $book->update(['is_active' => false]);

        return to_route('admin.books.index')->with('success', 'Buku dinonaktifkan. Riwayat peminjaman tetap tersimpan.');
    }

    /** @return array<string, mixed> */
    private function validatedData(Request $request, ?Book $book = null): array
    {
        $data = $request->validate(['category_id' => ['required', 'integer', 'exists:categories,id'], 'title' => ['required', 'string', 'max:255'], 'author' => ['required', 'string', 'max:255'], 'publisher' => ['nullable', 'string', 'max:255'], 'publication_year' => ['nullable', 'integer', 'min:1000', 'max:'.(now()->year + 1)], 'isbn' => ['nullable', 'string', 'max:20', Rule::unique('books', 'isbn')->ignore($book?->id)], 'description' => ['nullable', 'string'], 'total_stock' => ['required', 'integer', 'min:0'], 'available_stock' => ['required', 'integer', 'min:0', 'lte:total_stock'], 'is_active' => ['sometimes', 'boolean'], 'cover_image' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp', 'max:2048']]);
        $data['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : ($book?->is_active ?? true);

        return $data;
    }

    private function storeCover(Request $request): ?string
    {
        return $request->hasFile('cover_image') ? $request->file('cover_image')->store('covers', 'public') : null;
    }

    private function deleteCover(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
