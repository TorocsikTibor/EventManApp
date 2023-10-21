<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventJoinRequest extends Pivot
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'event_join_request';

    public function userJoin()
    {
        return $this->belongsTo(User::class);
    }

    public function eventJoin()
    {
        return $this->belongsTo(Event::class);
    }

}
