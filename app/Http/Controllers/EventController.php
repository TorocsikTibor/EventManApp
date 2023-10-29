<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventUser;
use App\Models\EventVisibility;
use App\Models\User;
use App\services\EventService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    private EventService $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

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

        $this->eventService->updateOrCreate(
            $validated['name'],
            $validated['date'],
            $validated['location'],
            $request->file('image') ?? "",
            $validated['type'],
            $validated['description'],
            $is_private,
            $request->input('users'),
        );

        return response()->json([
            'status' => 200,
            'message' => 'Event created successfully',
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

        $is_private = isset($request->checkbox) ? 1 : 0;
        $validated = $validator->validated();
        $imageName = Event::find($id);

        $this->eventService->updateOrCreate(
            $validated['name'],
            $validated['date'],
            $validated['location'],
            $request->file('image') ?? $imageName->image,
            $validated['type'],
            $validated['description'],
            $is_private,
            $request->input('users'),
        );

        return response()->json([
            'status' => 200,
            'message' => 'Event updated successfully',
        ]);

    }

    public function delete(int $id)
    {
        Event::destroy($id);

        return response()->json([
            'message' => 'success',
            'status' => 200,
        ]);
    }

    public function attend(int $id)
    {
        $eventUser = new EventUser();
        $eventUser->user_id = Auth::id();
        $eventUser->event_id = $id;
        $eventUser->save();

        return response()->json([
            'status' => 200,
            'message' => 'Event join successfully',
        ]);
    }
}
