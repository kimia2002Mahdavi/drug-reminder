<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReminderSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'medication_id',
        'reminder_time', // یا 'remind_at' اگر در مایگریشن اسم ستون رو تغییر دادید
        'status',
        'message',
        'user_id', // اگر تصمیم به نگه داشتن این کلید خارجی دارید
    ];

    /**
     * Get the user that owns the reminder schedule.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the medication that owns the reminder schedule.
     */
    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }
}