<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventVisibility;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function showCreate(): View|Application|Factory
    {
        $users = User::all();

        return view('event/create', ['users' => $users]);
    }

    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'date' => 'required',
            'location' => 'required',
            'image' => 'mimes:jpg,jpeg,png,bmp,tiff',
            'type' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $validated = $validator->validated();
        $is_private = isset($request->checkbox) ? 1 : 0;


        $event = new Event();
        $event->owner_id = Auth::id();
        $event->name = $validated['name'];
        $event->date = $validated['date'];
        $event->location = $validated['location'];

        $file = $request->file('image');
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('public/images'), $filename);
        $event->image = $filename;

        $event->type = $validated['type'];
        $event->description = $validated['description'];
        $event->is_private = $is_private;
        $event->save();

        if (isset($request->users)) {
            foreach ($request->users as $user) {
                $eventVisibility = new EventVisibility();
                $eventVisibility->user_id = $user;
                $eventVisibility->event_id = $event->id;
                $eventVisibility->save();
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Competitor created successfully',
        ]);
    }

    public function showUpdate(int $id)
    {
        $event = Event::find($id);
        $users = User::all();
        $eventvisibility = EventVisibility::where('event_id', $id)->get();

        $selectedUsers = [];
        foreach ($eventvisibility as $userVisible) {
            $selectedUsers[] = $userVisible->user_id;
        }

        return view('event/update', [ 'event' => $event, 'users' => $users, 'selectedUsers' => $selectedUsers ]);
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'date' => 'required',
            'location' => 'required',
            'image' => 'mimes:jpg,jpeg,png,bmp,tiff',
            'type' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $validated = $validator->validated();
        $is_private = isset($request->checkbox) ? 1 : 0;



        $event = Event::find($id);
        $event->owner_id = Auth::id();
        $event->name = $validated['name'];
        $event->date = $validated['date'];
        $event->location = $validated['location'];

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('public/images'), $filename);
            $event->image = $filename;
        }

        $event->type = $validated['type'];
        $event->description = $validated['description'];
        $event->is_private = $is_private;
        $event->save();

        if (isset($request->users)) {
            EventVisibility::where('event_id', $event->id)->delete();
            foreach ($request->users as $user) {
                $eventVisibility = new EventVisibility();
                $eventVisibility->user_id = $user;
                $eventVisibility->event_id = $event->id;
                $eventVisibility->save();
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Competitor created successfully',
        ]);

    }
}
