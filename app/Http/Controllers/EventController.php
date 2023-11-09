<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateEvent;
use App\Models\Event;
use App\Models\EventVisibility;
use App\Models\User;
use App\services\EventService;
use App\services\SearchService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function showCreate(): View|Application|Factory
    {
        $users = User::all()->except(Auth::id());

        return view('event/create', ['users' => $users]);
    }

    public function create(ValidateEvent $request, EventService $eventService): JsonResponse
    {
        $validated = $request->validated();
        $isPrivate = $request->has('checkbox');

        $eventService->updateOrCreate(
            $validated['name'],
            $validated['date'],
            $validated['location'],
            $request->file('image') ?? "",
            $validated['type'],
            $validated['description'],
            $isPrivate,
            $request->input('users')
        );

        return response()->json([
            'status' => 200,
            'message' => 'Event created successfully',
        ]);

    }

    public function showUpdate(int $id): View|Application|Factory
    {
        $event = Event::find($id);
        $users = User::all()->except(Auth::id());
        $eventVisibility = EventVisibility::where('event_id', $id)->get();

        $selectedUsers = [];
        foreach ($eventVisibility as $userVisible) {
            $selectedUsers[] = $userVisible->user_id;
        }

        return view('event/update', [
            'event' => $event,
            'users' => $users,
            'selectedUsers' => $selectedUsers,
        ]);
    }

    public function update(ValidateEvent $request, EventService $eventService, int $id): JsonResponse
    {
        $isPrivate = isset($request->checkbox);
        $validated = $request->validated();
        $imageName = Event::find($id);

        $eventService->updateOrCreate(
            $validated['name'],
            $validated['date'],
            $validated['location'],
            $request->file('image') ?? $imageName->image,
            $validated['type'],
            $validated['description'],
            $isPrivate,
            $request->input('users'),
        );

        return response()->json([
            'status' => 200,
            'message' => 'Event updated successfully',
        ]);

    }

    public function delete(int $id, EventService $eventService): JsonResponse
    {
        $eventService->delete($id);

        return response()->json([
            'message' => 'success',
            'status' => 200,
        ]);
    }

    public function attend(int $id, EventService $eventService): JsonResponse
    {
        $eventService->attend($id);

        return response()->json([
            'status' => 200,
            'message' => 'Event join successfully',
        ]);
    }

    public function search(Request $request, SearchService $searchService): JsonResponse
    {
        return $searchService->search(
            $request->input('searchValue'),
            $request->input('select')
        );
    }
}
