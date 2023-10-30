<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventVisibility extends Pivot
{
    use HasFactory;

    protected $fillable = ['user_id', 'event_id'];
    protected $table = 'event_visibility';
    public $timestamps = false;

    public function userVisibility()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function eventVisibility()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

}
