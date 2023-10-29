<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $visibleEvents = Event::with('userVisibility:id')->where('is_private', 0)->orWhereHas('userVisibility', function ($q) {
            $q->where('users.id', Auth::id());
        })->get();

        $joinedEventIds = [];

        $eventUsers = Event::with('user:id')->whereHas('user', function ($q) {
            $q->where('users.id', Auth::id());
        })->get();

        foreach ($eventUsers as $eventUser) {
            $joinedEventIds[] = $eventUser->id;
        }

        return view('home', ['events' => $visibleEvents, 'joinedEventIds' => $joinedEventIds]);
    }

    public function search(Request $request)
    {
        $searchValue = $request->input('searchValue');
        $inputValue = $request->input('select');

        $searchedEvents = Event::with('userVisibility:id')
            ->where(function ($q) use ($inputValue, $searchValue) {
                $q->where('is_private', 0) // Get non-private events
                ->orWhere(function ($sq) use ($inputValue, $searchValue) {
                    $sq->where('is_private', 1) // Get private events
                    ->whereHas('userVisibility', function ($q) {
                        $q->where('users.id', Auth::id());
                    });
                });
            })
            ->where("$inputValue", 'LIKE', '%'.$searchValue.'%')->with('eventOwner')
            ->get();

            return response()->json($searchedEvents);

    }
}
