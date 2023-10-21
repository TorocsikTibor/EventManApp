<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventVisibility extends Pivot
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'event_visibility';

    public function userVisibility()
    {
        return $this->belongsTo(User::class);
    }

    public function eventVisibility()
    {
        return $this->belongsTo(Event::class);
    }

}
