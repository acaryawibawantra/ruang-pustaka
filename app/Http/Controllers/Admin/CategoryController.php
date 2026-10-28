<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', ['categories' => Category::query()->withCount('books')->orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.categories.create', ['category' => new Category]);
    }

    public function store(Request $request): RedirectResponse
    {
        Category::create($this->validatedData($request));

        return to_route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update($this->validatedData($request, $category));

        return to_route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->books()->exists()) {
            return to_route('admin.categories.index')->with('error', 'Kategori masih dipakai buku. Pindahkan buku ke kategori lain terlebih dahulu.');
        }
        try {
            DB::transaction(fn () => $category->delete());
        } catch (QueryException) {
            return to_route('admin.categories.index')->with('error', 'Kategori masih dipakai buku. Pindahkan buku ke kategori lain terlebih dahulu.');
        }

        return to_route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    /** @return array<string, string|null> */
    private function validatedData(Request $request, ?Category $category = null): array
    {
        return $request->validate(['name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category?->id)], 'description' => ['nullable', 'string']]);
    }
}
