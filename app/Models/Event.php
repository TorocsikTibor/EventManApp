<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $guarded = ['id'];

    public function eventOwner(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(EventUser::class);
    }

    public function userjoin(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(EventJoinRequest::class);
    }

    public function userVisiblity(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(EventVisibility::class);
    }

}
