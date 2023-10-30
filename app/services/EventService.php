<?php

namespace App\services;

use App\Models\Event;
use App\Models\EventUser;
use App\Models\EventVisibility;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function updateOrCreate(
        string $name,
        string $date,
        string $location,
        mixed $file,
        string $type,
        string $description,
        int $isPrivate,
        mixed $users
    ): void {
        if (is_file($file)) {
            $filename = date('YmdHis') . $file->getClientOriginalName();
            $file->move(public_path('public/images'), $filename);
        }
        DB::beginTransaction();
        try {

            $event = Event::updateOrCreate(
                [
                    'name' => $name,
                    'owner_id' => Auth::id(),
                    'date' => $date,
                ],
                [
                    'location' => $location,
                    'image' => $filename ?? $file,
                    'type' => $type,
                    'description' => $description,
                    'is_private' => $isPrivate,
                ]);

            if (isset($users)) {
                EventVisibility::where('event_id', $event->id)->delete();
                foreach ($users as $user) {
                    EventVisibility::updateOrCreate(
                        [
                            'user_id' => $user,
                            'event_id' => $event->id,
                        ],
                        []
                    );
                }
            }
            DB::commit();
        } catch (Exception) {
            DB::rollback();
        }
    }

    public function delete(int $id): void
    {
        Event::destroy($id);
    }

    public function attend(int $id): void
    {
        $eventUser = new EventUser();
        $eventUser->user_id = Auth::id();
        $eventUser->event_id = $id;
        $eventUser->save();
    }
}
