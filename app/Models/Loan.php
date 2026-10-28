<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'book_id', 'borrowed_at', 'due_at', 'returned_at', 'status'])]
class Loan extends Model
{
    public const STATUS_BORROWED = 'borrowed';

    public const STATUS_RETURNED = 'returned';

    protected $attributes = ['status' => self::STATUS_BORROWED];

    protected $appends = ['is_overdue', 'is_approaching_due', 'urgency_status', 'urgency_label'];

    use HasFactory;

    protected function casts(): array
    {
        return [
            'borrowed_at' => 'date',
            'due_at' => 'date',
            'returned_at' => 'date',
        ];
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === self::STATUS_BORROWED
            && $this->returned_at === null
            && $this->due_at !== null
            && $this->due_at->lt(today());
    }

    public function getIsApproachingDueAttribute(): bool
    {
        return $this->status === self::STATUS_BORROWED
            && $this->returned_at === null
            && $this->due_at !== null
            && ! $this->is_overdue
            && today()->diffInDays($this->due_at) <= 3;
    }

    public function getUrgencyStatusAttribute(): string
    {
        if ($this->status === self::STATUS_RETURNED) {
            return 'returned';
        }

        if ($this->is_overdue) {
            return 'overdue';
        }

        if ($this->is_approaching_due) {
            return 'approaching';
        }

        return 'active';
    }

    public function getUrgencyLabelAttribute(): string
    {
        if ($this->status === self::STATUS_RETURNED) {
            return 'Dikembalikan';
        }

        if ($this->is_overdue) {
            $days = (int) $this->due_at->diffInDays(today());

            return 'Terlambat'.($days > 0 ? " • Lewat {$days} hari" : '');
        }

        $days = (int) today()->diffInDays($this->due_at);
        if ($days === 0) {
            return 'Jatuh tempo hari ini';
        }

        if ($this->is_approaching_due) {
            return "Mendekati tempo • {$days} hari lagi";
        }

        return "Dipinjam • {$days} hari lagi";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
