<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventVisibility;
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
        $visibleEvents = Event::where('is_private', 0)->get();

        $privateEvents = Event::where('is_private', 1)->with('userVisibility:id,name')->get();

        foreach ($privateEvents as $privateEvent) {
            foreach ($privateEvent->userVisibility as $privateUser) {
                if ($privateUser->id === Auth::id()) {
                    $visibleEvents = $visibleEvents->push($privateEvent);
                }
            }
        }

        return view('home', ['events' => $visibleEvents]);
    }
}
