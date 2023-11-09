<?php

namespace App\services;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SearchService
{
    public function search($searchValue, $inputValue): JsonResponse
    {
        $searchedEvents = Event::with('userVisibility:id')
            ->where(function ($q) use ($inputValue, $searchValue) {
                $q->where('is_private', 0)
                ->orWhere(function ($sq) use ($inputValue, $searchValue) {
                    $sq->where('is_private', 1)
                    ->whereHas('userVisibility', function ($q) {
                        $q->where('users.id', Auth::id());
                    });
                });
            })
            ->where("$inputValue", 'LIKE', '%'.$searchValue.'%')->with('eventOwner')
            ->get()
            ->map(function (Event $event) {
                $event->image = $event->getImagePath();
                return $event;
            });

        $response = [ 'searchedEvents' => $searchedEvents, 'AuthId' => Auth::id() ];

        return response()->json($response);
    }
}
