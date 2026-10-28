# Member Dashboard (Card-Stack Urgency Hub)

## Problem Statement
How might we give library members an immediate, glanceable overview of their active book loans and return deadlines on a dedicated `/dashboard`, so they instantly know which books need urgent attention without digging through full loan histories?

## Recommended Direction
Build a dedicated `/dashboard` for authenticated members using a **Card-Stack Urgency Hub (Direction A)**. University borrowers typically hold 1–4 books at a time, making high-density tabular ledgers unnecessary and visually unengaging on mobile screens. A responsive card-based layout prioritizes books currently in the member's possession, placing overdue and soon-expiring loans at the top of the screen.

Each active loan card displays book cover/title, author, due date, and a color-coded status badge with human-readable relative time:
- 🔴 **Terlambat** (is_overdue === true, e.g. "Terlambat • Lewat 3 hari")
- 🟡 **Mendekati Tempo** (0 <= diffInDays <= 3, e.g. "Mendekati tempo • 2 hari lagi" or "Jatuh tempo hari ini")
- 🟢 **Dipinjam** (diffInDays > 3, e.g. "Dipinjam • 11 hari lagi")

Below the active cards tier, a calm zero-state is shown if no books are borrowed, and a compact 3-5 item table displays recently returned books with a direct link to the full `/pinjaman` history.

## Key Assumptions to Validate
- [ ] **Navigation & Entry Point:** Members immediately find and land on `/dashboard` upon login, and the public navigation bar links directly to Dashboard for logged-in members (test via browser / feature tests simulating login redirect and navbar links).
- [ ] **Urgency Calculation Accuracy:** Relative date math (`diffInDays`) compares calendar dates (`today()`) accurately so that books due today or tomorrow don't flicker or miscalculate due to hour/minute differences (test via unit/feature tests around boundary dates).
- [ ] **Clarity without Actions:** Members understand their deadline status without needing an in-app renewal button, knowing to visit the library before or on the due date (test via UI copy and clear status badges).

## MVP Scope
- **Route:** `GET /dashboard` protected by `auth` and `active` middleware, routing to `App\Http\Controllers\Member\DashboardController`.
- **Top Tier (Active Loans):** Responsive cards showing active loans (`status = borrowed`, `returned_at is null`), sorted by `due_at ASC` (oldest overdue first).
- **Status Badges & Countdowns:**
  - `Terlambat` badge (crimson) with days overdue.
  - `Mendekati Tempo` badge (amber) with days remaining (when <= 3 days).
  - `Dipinjam` badge (brand/neutral) with days remaining.
- **Empty State:** If no active loans, display a friendly empty state ("Tidak ada pinjaman aktif") with a button to explore the catalog.
- **Bottom Tier (Recent History):** Compact table showing the 3 most recently returned loans, with a link to `route('member.loans.index')`.
- **Navigation Update:** Update the navigation header in `resources/views/components/layouts/public.blade.php` to include "Dashboard" for authenticated members, and redirect authenticated regular members from login / `/` appropriately.

## Not Doing (and Why)
- **Self-service Loan Extensions / Renewals:** User explicitly requested status badges only; renewals require library policy verification, staff approvals, and complex rule checks.
- **Fine / Denda Calculation Engine:** No fine tracking exists in current database schema; tracking fines adds unwanted scope to a member overview dashboard.
- **Real-time Push / SMS Alerts:** Out of scope for a web dashboard MVP; notifications can be added as a separate channel later.

## Open Questions
- None blocking MVP: Date boundaries (<= 3 days) and badges are confirmed and aligned with existing project conventions.
