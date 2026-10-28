<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100'], 'status' => ['nullable', Rule::in(['active', 'inactive'])]]);
        $search = trim($filters['q'] ?? '');
        $members = User::query()->where('role', User::ROLE_MEMBER)->when($search !== '', fn ($query) => $query->where(fn ($query) => $query->whereLike('name', '%'.$search.'%')->orWhereLike('nrp_nim', '%'.$search.'%')->orWhereLike('email', '%'.$search.'%')))->when(isset($filters['status']), fn ($query) => $query->where('is_active', $filters['status'] === 'active'))->orderBy('name')->orderBy('id')->paginate(15)->withQueryString();

        return view('admin.members.index', ['members' => $members, 'search' => $search, 'selectedStatus' => $filters['status'] ?? '']);
    }

    public function show(User $member): View
    {
        abort_unless($member->role === User::ROLE_MEMBER, 404);

        return view('admin.members.show', ['member' => $member, 'loans' => $member->loans()->with('book')->latest('borrowed_at')->paginate(12)]);
    }

    public function edit(User $member): View
    {
        abort_unless($member->role === User::ROLE_MEMBER, 404);

        return view('admin.members.edit', ['member' => $member]);
    }

    public function update(Request $request, User $member): RedirectResponse
    {
        abort_unless($member->role === User::ROLE_MEMBER, 404);
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'nrp_nim' => ['required', 'string', 'max:32', Rule::unique('users', 'nrp_nim')->ignore($member->id)], 'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($member->id)], 'is_active' => ['sometimes', 'boolean']]);
        $data['is_active'] = $request->boolean('is_active');
        $member->update($data);

        return to_route('admin.members.show', $member)->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function toggleStatus(User $member): RedirectResponse
    {
        abort_unless($member->role === User::ROLE_MEMBER, 404);
        $member->update(['is_active' => ! $member->is_active]);

        return to_route('admin.members.index')->with('success', 'Status anggota berhasil diperbarui. Riwayat peminjaman tetap tersimpan.');
    }
}
