<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'integer', 'exists:categories,id'],
        ]);
        $search = trim($filters['q'] ?? '');
        $category = $filters['category'] ?? null;

        $books = Book::query()->where('is_active', true)->with('category')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->whereLike('title', '%'.$search.'%')->orWhereLike('author', '%'.$search.'%');
                });
            })
            ->when($category, fn (Builder $query): Builder => $query->where('category_id', $category))
            ->orderBy('title')->orderBy('id')->paginate(12)->withQueryString();

        return view('catalog.index', [
            'books' => $books,
            'categories' => Category::query()->orderBy('name')->get(),
            'search' => $search,
            'selectedCategory' => $category,
        ]);
    }

    public function show(Book $book): View
    {
        abort_unless($book->is_active, 404);

        return view('catalog.show', ['book' => $book->load('category')]);
    }
}
