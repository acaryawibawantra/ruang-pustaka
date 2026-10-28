# Member Dashboard Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a dedicated `/dashboard` for authenticated library members displaying a card-stack urgency hub for active loans, deadline countdowns, overdue badges, and a summary of recent returns.

**Architecture:** A single-action `App\Http\Controllers\Member\DashboardController` retrieves the authenticated member's active loans sorted chronologically by due date (`due_at ASC`) alongside their recent return history. Domain urgency logic (overdue, approaching due date <= 3 days, active, returned) is encapsulated on the `Loan` model through explicit accessors, keeping the Blade view clean and declarative.

**Tech Stack:** Laravel 12, PHP 8.4, Tailwind CSS, Blade components, PHPUnit.

**Spec:** `docs/ideas/member-dashboard.md`

## Global Constraints
- PHP 8.4: Explicit parameter and return type declarations on all methods; constructor property promotion where applicable.
- Styling: Tailwind CSS classes consistent with `resources/views/admin/dashboard.blade.php` and `resources/views/member/loans/index.blade.php`.
- Access Control: Route protected by `auth` and `active` middleware; users can only see their own loans.
- Formatting: Run `vendor/bin/pint --dirty --format agent` before finalizing changes.
- Graphify: Run `graphify update .` after code modifications.

## Review Focus
- Boundary date comparison: Books due today (`due_at == today()`) or in <= 3 days must correctly resolve to `approaching` rather than miscalculating as overdue or distant active.
- Overdue loans with no return date: Verify that only unreturned loans past `today()` show as overdue.
- Authorization barrier: Guest and inactive users must not access `/dashboard`; users cannot view other users' active loans or stats.
- Zero-loan member experience: When a user has 0 active loans, the dashboard renders a helpful empty state with a catalog link instead of empty containers.
- Public layout navigation: The main navigation bar correctly displays the "Dashboard" link for authenticated members.

---

### Task 1: Model Urgency Logic on Loan

**Files:**
- Modify: `app/Models/Loan.php`
- Create: `tests/Unit/LoanUrgencyTest.php`

- [ ] **Step 1: Write failing unit test for loan urgency accessors**
  Write `tests/Unit/LoanUrgencyTest.php` testing:
  - `is_approaching_due` returns true when unreturned and due within 0 to 3 days from today.
  - `is_approaching_due` returns false when overdue, returned, or due in > 3 days.
  - `urgency_status` returns `'overdue'`, `'approaching'`, `'active'`, or `'returned'`.
  - `urgency_label` returns human-readable text including days remaining or days late.
- [ ] **Step 2: Run unit test to confirm RED**
  Run `php artisan test --filter=LoanUrgencyTest --compact`.
  Expected: Fails because accessors do not exist on `Loan`.
- [ ] **Step 3: Implement urgency accessors on `Loan` model**
  In `app/Models/Loan.php`, add:
  - `is_approaching_due`: checks `status === STATUS_BORROWED && returned_at === null && due_at !== null && ! $this->is_overdue && today()->diffInDays($this->due_at) <= 3`.
  - `urgency_status`: returns `'returned'`, `'overdue'`, `'approaching'`, or `'active'`.
  - `urgency_label`: returns descriptive label with countdown.
- [ ] **Step 4: Run unit test to confirm GREEN**
  Run `php artisan test --filter=LoanUrgencyTest --compact`.
  Expected: Passes with all assertions green.
- [ ] **Step 5: Commit Task 1**
  Run `git add app/Models/Loan.php tests/Unit/LoanUrgencyTest.php && git commit -m "feat(loan): add urgency status and countdown accessors"`.

---

### Task 2: Controller & Route Setup (`Member\DashboardController`)

**Files:**
- Create: `app/Http/Controllers/Member/DashboardController.php`
- Modify: `routes/web.php`
- Create: `tests/Feature/MemberDashboardTest.php`

- [ ] **Step 1: Write failing feature test for dashboard routing and controller data**
  Write `tests/Feature/MemberDashboardTest.php`:
  - `test_guest_redirected_to_login_from_dashboard()`: Unauthenticated request to `/dashboard` redirects to `login`.
  - `test_inactive_member_is_blocked_from_dashboard()`: Inactive user cannot access `/dashboard`.
  - `test_member_sees_only_own_active_loans_ordered_by_due_date()`: Active loans sorted `due_at ASC`, excluding other users' loans.
  - `test_dashboard_provides_summary_metrics()`: Active count, overdue count, and returned count are calculated accurately.
- [ ] **Step 2: Run test to confirm RED**
  Run `php artisan test --filter=MemberDashboardTest --compact`.
  Expected: Fails because route `/dashboard` does not exist.
- [ ] **Step 3: Implement `Member\DashboardController` and register route**
  - Create `app/Http/Controllers/Member/DashboardController.php` fetching `$activeLoans`, `$recentReturnedLoans`, and `$metrics`.
  - Register route in `routes/web.php` under `['auth', 'active']` middleware:
    `Route::get('/dashboard', \App\Http\Controllers\Member\DashboardController::class)->name('dashboard');`
- [ ] **Step 4: Run test to confirm GREEN**
  Run `php artisan test --filter=MemberDashboardTest --compact`.
  Expected: Passes.
- [ ] **Step 5: Commit Task 2**
  Run `git add app/Http/Controllers/Member/DashboardController.php routes/web.php tests/Feature/MemberDashboardTest.php && git commit -m "feat(member): add member dashboard controller and route"`.

---

### Task 3: Blade View & Layout Navigation

**Files:**
- Create: `resources/views/member/dashboard.blade.php`
- Modify: `resources/views/components/layouts/public.blade.php`
- Modify: `tests/Feature/MemberDashboardTest.php`

- [ ] **Step 1: Write assertions in feature test for UI view elements**
  Add test methods to `tests/Feature/MemberDashboardTest.php`:
  - `test_dashboard_renders_badges_for_overdue_approaching_and_active_loans()`: Asserts status badge classes and copy appear.
  - `test_dashboard_renders_empty_state_when_no_active_loans()`: Asserts empty state component is displayed.
  - `test_navigation_header_shows_dashboard_link_for_authenticated_member()`: Asserts `/dashboard` link is present in navigation when logged in.
- [ ] **Step 2: Run test to confirm RED**
  Run `php artisan test --filter=MemberDashboardTest --compact`.
  Expected: Fails because view is not yet created / elements missing.
- [ ] **Step 3: Create `member/dashboard.blade.php` and update `public.blade.php`**
  - Create `resources/views/member/dashboard.blade.php` with:
    - Welcome header & quick stat cards (Buku Dipinjam, Perlu Perhatian, Riwayat Selesai).
    - Active loan cards with status badges (Terlambat, Mendekati Tempo, Dipinjam) and relative countdowns.
    - Empty state when no active loans.
    - Recent returns compact table with link to `route('member.loans.index')`.
  - Update `resources/views/components/layouts/public.blade.php`:
    - Add "Dashboard" link for authenticated members.
    - Clean up malformed comment text in header SVG.
- [ ] **Step 4: Run test to confirm GREEN**
  Run `php artisan test --filter=MemberDashboardTest --compact`.
  Expected: All tests pass.
- [ ] **Step 5: Commit Task 3**
  Run `git add resources/views/member/dashboard.blade.php resources/views/components/layouts/public.blade.php tests/Feature/MemberDashboardTest.php && git commit -m "feat(member): implement member dashboard view and update navigation"`.

---

### Task 4: Polish, Pint & Graphify

**Files:**
- Checked / formatted by Pint.
- Updated: `graphify-out/graph.json`

- [ ] **Step 1: Run Pint linter and formatter**
  Run `vendor/bin/pint --dirty --format agent`.
- [ ] **Step 2: Run full Member test suite**
  Run `php artisan test --compact --filter=Member`.
  Expected: 100% passing tests.
- [ ] **Step 3: Update knowledge graph**
  Run `graphify update .`.
- [ ] **Step 4: Commit Task 4**
  Run `git add . && git commit -m "chore: style fixes and graphify update for member dashboard"`.
