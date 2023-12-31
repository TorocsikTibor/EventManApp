<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $fillable = ['user_id', 'name', 'date', 'location', 'image', 'type', 'description', 'is_private'];

    public function getImagePath(): string
    {
        return asset('images' . DIRECTORY_SEPARATOR . $this->image);
    }

    public function eventOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(EventUser::class);
    }

    public function userVisibility(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_visibility', 'event_id', 'user_id');
    }

}
