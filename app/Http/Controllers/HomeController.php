<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventUser;
use App\Models\EventVisibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Symfony\Component\String\b;

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
        $visibleEvents = Event::where('is_private', 0)->with('user:id,name')->get();

        $privateEvents = Event::where('is_private', 1)->with(['userVisibility:id,name' , 'user:id,name'])->get();

        foreach ($privateEvents as $privateEvent) {
            foreach ($privateEvent->userVisibility as $privateUser) {
                if ($privateUser->id === Auth::id()) {
                    $visibleEvents = $visibleEvents->push($privateEvent);
                }
            }
        }

        $joinedEventIds = [];

        $eventUsers = Event::with('user:id')->whereHas('user', function ($q) {
            $q->where('users.id', Auth::id());
        })->get();

        foreach ($eventUsers as $eventUser) {
            $joinedEventIds[] = $eventUser->id;
        }

//        dd($joinedEventIds);
        return view('home', ['events' => $visibleEvents, 'joinedEventIds' => $joinedEventIds]);
    }
}
