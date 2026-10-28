<?php

namespace Tests\Unit;

use App\Models\Loan;
use Tests\TestCase;

class LoanUrgencyTest extends TestCase
{
    public function test_overdue_loan_has_overdue_status_and_label(): void
    {
        $loan = new Loan([
            'status' => Loan::STATUS_BORROWED,
            'borrowed_at' => today()->subDays(10),
            'due_at' => today()->subDays(3),
            'returned_at' => null,
        ]);

        $this->assertTrue($loan->is_overdue);
        $this->assertFalse($loan->is_approaching_due);
        $this->assertSame('overdue', $loan->urgency_status);
        $this->assertSame('Terlambat • Lewat 3 hari', $loan->urgency_label);
    }

    public function test_loan_due_today_is_approaching_with_today_label(): void
    {
        $loan = new Loan([
            'status' => Loan::STATUS_BORROWED,
            'borrowed_at' => today()->subDays(7),
            'due_at' => today(),
            'returned_at' => null,
        ]);

        $this->assertFalse($loan->is_overdue);
        $this->assertTrue($loan->is_approaching_due);
        $this->assertSame('approaching', $loan->urgency_status);
        $this->assertSame('Jatuh tempo hari ini', $loan->urgency_label);
    }

    public function test_loan_due_within_three_days_is_approaching(): void
    {
        $loan = new Loan([
            'status' => Loan::STATUS_BORROWED,
            'borrowed_at' => today()->subDays(5),
            'due_at' => today()->addDays(2),
            'returned_at' => null,
        ]);

        $this->assertFalse($loan->is_overdue);
        $this->assertTrue($loan->is_approaching_due);
        $this->assertSame('approaching', $loan->urgency_status);
        $this->assertSame('Mendekati tempo • 2 hari lagi', $loan->urgency_label);
    }

    public function test_loan_due_beyond_three_days_is_active(): void
    {
        $loan = new Loan([
            'status' => Loan::STATUS_BORROWED,
            'borrowed_at' => today()->subDays(2),
            'due_at' => today()->addDays(7),
            'returned_at' => null,
        ]);

        $this->assertFalse($loan->is_overdue);
        $this->assertFalse($loan->is_approaching_due);
        $this->assertSame('active', $loan->urgency_status);
        $this->assertSame('Dipinjam • 7 hari lagi', $loan->urgency_label);
    }

    public function test_returned_loan_has_returned_status_regardless_of_dates(): void
    {
        $loan = new Loan([
            'status' => Loan::STATUS_RETURNED,
            'borrowed_at' => today()->subDays(14),
            'due_at' => today()->subDays(2),
            'returned_at' => today()->subDays(1),
        ]);

        $this->assertFalse($loan->is_overdue);
        $this->assertFalse($loan->is_approaching_due);
        $this->assertSame('returned', $loan->urgency_status);
        $this->assertSame('Dikembalikan', $loan->urgency_label);
    }
}
