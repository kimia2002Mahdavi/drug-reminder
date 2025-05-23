<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medication extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'dosage', // نام فیلد 'dosage' به درستی در fillable هست.
        'interval_type',
        'interval_value',
        'user_id', // 'user_id' هم به درستی در fillable هست.
    ];

    /**
     * Get the user that owns the medication.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reminder schedules for the medication.
     */
    public function reminderSchedules(): HasMany 
    {
        return $this->hasMany(ReminderSchedule::class);
    }
}