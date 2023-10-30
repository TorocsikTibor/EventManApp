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

class EventController extends Controller
{
    private EventService $eventService;
    private SearchService $searchService;

    public function __construct(EventService $eventService, SearchService $searchService)
    {
        $this->eventService = $eventService;
        $this->searchService = $searchService;
    }

    public function showCreate(): View|Application|Factory
    {
        $users = User::all();

        return view('event/create', ['users' => $users]);
    }

    public function create(ValidateEvent $request): JsonResponse
    {
            $validated = $request->validated();
            $is_private = $request->has('checkbox') ? 1 : 0;

            $this->eventService->updateOrCreate(
                $validated['name'],
                $validated['date'],
                $validated['location'],
                $request->file('image') ?? "",
                $validated['type'],
                $validated['description'],
                $is_private,
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
        $users = User::all();
        $eventvisibility = EventVisibility::where('event_id', $id)->get();

        $selectedUsers = [];
        foreach ($eventvisibility as $userVisible) {
            $selectedUsers[] = $userVisible->user_id;
        }

        return view('event/update', [ 'event' => $event, 'users' => $users, 'selectedUsers' => $selectedUsers ]);
    }

    public function update(ValidateEvent $request, int $id): JsonResponse
    {
        $is_private = isset($request->checkbox) ? 1 : 0;
        $validated = $request->validated();
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

    public function delete(int $id): JsonResponse
    {
        $this->eventService->delete($id);

        return response()->json([
            'message' => 'success',
            'status' => 200,
        ]);
    }

    public function attend(int $id): JsonResponse
    {
        $this->eventService->attend($id);

        return response()->json([
            'status' => 200,
            'message' => 'Event join successfully',
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        return $this->searchService->search($request->input('searchValue'), $request->input('select'));
    }
}
