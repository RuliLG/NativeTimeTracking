<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('date', now()->month);
    }

    public function scopeCurrentWeek($query)
    {
        return $query->whereBetween('date', [today()->startOfWeek(), today()->endOfWeek()]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function duration(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->end_time->diffInMinutes($this->start_time),
        );
    }

    public function formattedDuration(): Attribute
    {
        return Attribute::make(
            get: fn () => sprintf('%01dh %02dm', $this->duration / 60, $this->duration % 60)
        );
    }
}
